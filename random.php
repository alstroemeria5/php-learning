<?php
class Mine{
  public $Array=[];
  public $MineDim;
  public $MineNum;
  public $ArrayString;
  public function __construct($MineDim,$MineNum){
    $this->MineDim=$MineDim;
    $this->MineNum=$MineNum;
    $RandList=[];
    $MaxRow=$MineDim[0];
    $MaxCol=$MineDim[1];
    $num=0;
    while($num<$MineNum){
      $row=rand(0,$MaxRow-1);
      $col=rand(0,$MaxCol-1);
      $temp=[$row,$col];
      if(!in_array($temp,$RandList)){
        $num+=1;
        array_push($RandList,$temp);
      }
    }
    for($i=0;$i<$MaxRow;$i++)
      for($j=0;$j<$MaxRow;$j++)
        if(in_array([$i,$j],$RandList)){
          $this->Array[$i][$j]=1;
        }
        else {
          $this->Array[$i][$j]=0;
        }
      $this->StringLize($this->Array,$this->MineDim,$this->MineNum);
  }
  public function StringLize($ArrayString,$MineDim,$MineNum){
    $ArrayString='';
    $MaxRow=$MineDim[0];
    $MaxCol=$MineDim[1];
    for($i=0;$i<$MaxRow;$i++)
      for($j=0;$j<$MaxRow;$j++)
        $ArrayString.=(string)$this->Array[$i][$j];
    $this->ArrayString=$ArrayString;

  }
}

class MineThread extends Thread{
  public $MineDim;
  public $MineNum;
  public $MineIns;
  protected $complete;
  public $shm_key;
  public function __construct($shm_key,$MineDim,$MineNum){
    $this->shm_key=$shm_key;
    $this->MineIns=new Mine($MineDim,$MineNum);
    $this->complete=false;
  }
  public function isGarbage() {
      return $this->complete;
  }
  public function run(){

  $shm_id = shmop_open($this->shm_key, "c", 0666, strlen($this->MineIns->ArrayString));

  if(!$shm_id)
  {
      echo "Couldn't create shared memory segment\n";
  }

  // Get the size of shared memory block
  $shm_size = shmop_size($shm_id);
  echo "SHM Block Size: ". $shm_size . " has been created.\n";
  // Write a test string into shared memory
  $shm_bytes_written = shmop_write($shm_id, $this->MineIns->ArrayString, 0);

  if($shm_bytes_written != strlen($this->MineIns->ArrayString))
  {

      echo "Couldn't write the entire length of data\n";
  }


    sleep(5);
  $this->complete=true;
  }
}

$pool=new Pool(10,worker::class);
$MineThreadArray=Array();
for($i=0;$i<5;$i++){
array_push($MineThreadArray,new MineThread($i+1,[5*($i+1),5*($i+1)],10*($i+1)));
}
for($i=0;$i<5;$i++){
$pool->submit($MineThreadArray[$i]);
}
$pool->shutdown();

for($i=0;$i<5;$i++){
$shm_id=shmop_open($i+1,"a",0666,strlen($MineThreadArray[$i]->MineIns->ArrayString));
$my_string = shmop_read($shm_id, 0, strlen($MineThreadArray[$i]->MineIns->ArrayString));

if(!$my_string)
{
    echo "Couldn't read from shared memory block\n";
    echo $i;
}

echo "The data inside shared memory was: ".$my_string."\n";

// Delete the block and close the shared memory segment

if(!shmop_delete($shm_id))
{
    echo "Couldn't mark shared memory block for deletion.";
}

  shmop_close($shm_id);
}

?>
