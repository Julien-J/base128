<?php

$buffer="Encode the world";

echo "Base128 encoding:\n";
echo base128::encode($buffer)."\n\n";

echo "Base128 decoding\n";
echo base128::decode(base128::encode($buffer));

class base128
{
	// iso 8859-1 removed chars <>?'"`+&/\
	private static $ascii='!#$%()*,.0123456789:;=@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_abcdefghijklmnopqrstuvwxyz{|}~¡¢£¤¥¦§¨©ª«¬®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎ';
			
	function encode ($buffer)
	{
		return self::encode_custom($buffer,self::$ascii);
	}
	
	function decode($buffer)
	{
		return self::decode_custom($buffer,self::$ascii);		
	}
	
	
	public function encode_custom($buffer,$ascii)
	{
		$size=strlen($buffer);		
		$size++;				// add an empty byte to the end		
		$ls=0;
		$rs=7;
		$r=0;
		$encoded="";
		
		for($inx=0;$inx<$size;$inx++)
		{
			if($ls>7)
			{	
				$inx--;
				$ls=0;
				$rs=7;
			}
			$nc=ord(substr($buffer,$inx,1));	
			$r1=$nc;				// save $nc
			$nc=$nc<<$ls;			// shift left for $rs
			$nc=($nc & 0x7f) | $r;	// OR carry bits		
			$r=$r1>>$rs;			// shift right and save carry bits		
			$ls++;
			$rs--;		
				
			$encoded.=substr($ascii,$nc,1);
		}
		return $encoded;	
	}
			
	function decode_custom($buffer,$ascii)
	{
		$size=strlen($buffer);
		$rs=8;
		$ls=7;
		$r=0;
		$decoded="";
		
		for($inx=0;$inx<$size;$inx++)
		{			
			$nc=strpos($ascii,substr($buffer,$inx,1));
			if($rs>7)
			{
				$rs=1;
				$ls=7;
				$r=$nc;
				continue;
			}	
			$r1=$nc;
			$nc=$nc<<$ls;
			$nc=$nc|$r;
			$r=$r1>>$rs;
			$rs++;
			$ls--;
			$decoded.=chr($nc);			
		}
		return $decoded;
	}
}
?>