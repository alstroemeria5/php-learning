<?php
class Mine{
  public $Array=[];
  public function __construct($MineDim,$MineNum){
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
  }

}
$MineA=new Mine([10,10],10);
for($i=0;$i<10;$i++){
  for($j=0;$j<10;$j++){
      echo $MineA->Array[$i][$j].' ';
  }
  echo "<br>";
}
?>
