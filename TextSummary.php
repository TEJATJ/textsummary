<?php 
/*
ARTIFICIAL INTELLIGENCE TERM PAPER (1st SEMESTER 2015-16)

SHREYANS MITTAL 2013A7PS032P (IMPLEMENTATION OF TEXTRANK)

HIMANSHU SINGH DHONI

K. SAI TEJA 2013A7PS034P (IMPLEMETATION OF SIMPLIFIED LESK ALGORITHM)





**********************LESK ALGORITHM IMPLEMENTATION BEGINS**********************
(IMPLEMENTED BY SAI TEJA K 2013A7PS034P)


EXTERNAL LIBRARIES USED: wordnet Dictionary
*/
require("Database.php");
class TextSummary
{
	
	private $db;
	private $text;
	private $sentences=array();
	private $text_array;
	private $frequencies=array();
	private $sentence_count=array();
	private $stopwords=array("a","able","about","across","after","all","almost","also","am","among","an","and","any","are","as","at","be",
  	"because","been","but","by","can","cannot","could","dear","did","do",
  	"does","either","else","ever","every","for","from","get","got","had",
  	"has","have","he","her","hers","him","his","how","however","i","if",
  	"in","into","is","it","its","just","least","let","like","likely","may",
  	"me","might","most","must","my","neither","no","nor","not","of","off","often",
  	"on","only","or","other","our","own","rather","said","say","says","she","should",
  	"since","so","some","than","that","the","their","them","then","there","these",
  	"they","this","tis","to","too","twas","us","wants","was","we","were","what","when",
  	"where","which","while","who","whom","why","will","with","would","yet","you",
  	"your","ain't","aren't","can't","could've","couldn't","didn't","doesn't",
  	"don't","hasn't","he'd","he'll","he's","how'd","how'll","how's","i'd","i'll",
  	"i'm","i've","isn't","it's","might've","mightn't","must've","mustn't","shan't",
  	"she'd","she'll","she's","should've","shouldn't","that'll","that's","there's",
  	"they'd","they'll","they're","they've","wasn't","we'd","we'll","we're","weren't",
  	"what'd","what's","when'd","when'll","when's","where'd","where'll","where's","who'd",
  	"who'll","who's","why'd","why'll","why's","won't","would've","wouldn't","you'd","you'll","you're","you've");
	
	public function __construct($text)
	{
		
		$this->text=$text;
		/* split $content into array of substrings of $content i.e wordwise */
		$wordArray = preg_split('/[^a-z]/', $text, -1, PREG_SPLIT_NO_EMPTY);
		 
		/* "stop words", filter them */
		$filteredArray = array_filter($wordArray, function($x){
		       return 		!preg_match("/^(a|able|about|across|after|all|almost|also|am|among|an|and|any|are|
       	as|at|be|because|been|but|by|can|cannot|could|dear|did|do|does|either|else|ever|every|for
       	|from|get|got|had|has|have|he|her|hers|him|his|how|however|i|if|in|into|is|it|its|just|least|
       	let|like|likely|may|me|might|most|must|my|neither|no|nor|not|of|off|often|on|only|or|other|our
       	|own|rather|said|say|says|she|should|since|so|some|than|that|the|their|them|then|there|these|they
       	|this|tis|to|too|twas|us|wants|was|we|were|what|when|where|which|while|who|whom|why|will|with|would
       	|yet|you|your|ain't|aren't|can't|could've|couldn't|didn't|doesn't|don't|hasn't|he'd|he'll|he's|how'd
       	|how'll|how's|i'd|i'll|i'm|i've|isn't|it's|might've|mightn't|must've|mustn't|shan't|she'd|she'll|she's
       	|should've|shouldn't|that'll|that's|there's|they'd|they'll|they're|they've|wasn't|we'd|we'll|we're|weren't
       	|what'd|what's|when'd|when'll|when's|where'd|where'll|where's|who'd|who'll|who's|why'd|why'll|why's|won't
       	|would've|wouldn't|you'd|you'll|you're|you've)$/",$x);
		     });
		 
		/* get associative array of values from $filteredArray as keys and their frequency count as value */
		$this->text_array = array_count_values($filteredArray);
 		
		$this->sentences=explode(".",$text);
	}

	public function frequency($words)
	{$frequency=0;
		foreach($words as $element2)
		{
			if(!in_array(($element2), $this->stopwords))
			$frequency+=$this->text_array[$element2];
			
		} 
		
		return $frequency;
	}
	public function mainP()
	{
		$sen=0;
		$db=new Database;
		foreach($this->sentences as $sentence)
		{

			$highest=0; 
			$words=array();
			$words=explode(" ",$sentence);
			
			foreach($words as $word)
			{
				if(!in_array(strtolower($word), $this->stopwords))
				{
					//Fetching the meaning definition of the senses From the WordNet
					$db->query("SELECT * from words where lemma like :word");
					$db->bind("word",$word);
					$wordid=$db->single()->wordid;
					$db->query("SELECT * from senses where wordid like :wordid"); 
					$db->bind("wordid",$wordid);
					$resulta2 = $db->resultset();
					foreach($resulta2 as $row)
					{
					  $synsetid=$row->synsetid;
					  $sample2[]=$synsetid;
					  $title = $row->wordid . " " . $row->senseid;
					  }
					
					$db->query("select * from synsets where synsetid like :sample"); 
					$db->bind("sample",$sample2[0]);
					$resulta3 = $db->resultset();
					
					unset($sample2[0]);
					
					 for($i=0;$i<(count($sample2)+1);$i++)
					 {
					  ${result_A.$i}=$sample2[$i];
					  ${lexdomains_var.$i}=$lexdomains[$i];
					    
					 $db->query("select * from synsets where synsetid like :sample");
					 $db->bind("sample",${result_A.$i});
					  ${result.$i} =$db->resultset();
					 foreach(${result.$i} as $row)
					  {
					  ${pos.$i}=$row->pos;
					  if(${pos.$i}=="s"){
					  ${pos.$i}="satellite adj";}
					
					  $def = $row->definition;
					  $def=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $def);
					  $definition=array();
					  $definition=explode(" ",$def );
					  $frequency=$this->frequency($definition);
					  if($frequency>$highest)
					  	$highest=$frequency;
					}
					
				}$sample2=array();


			}

		}
		$this->frequencies[]=$highest;		
		$this->sentence_count[]=$sen;
		$sen++;
	}
}
public function getSummary($fraction)
{
array_multisort($this->frequencies,SORT_DESC, $this->sentence_count);
$tot=count($this->sentence_count);
$new= $fraction*$tot;
$top_sentences=array_slice($this->sentence_count, 0,$new);
sort($top_sentences);
echo "<underline>Original Text</underline><br>";
echo $this->text;
echo '<br><br> Summary:-<br><br><pre>';
foreach($top_sentences as $sentence)
echo $this->sentences[$sentence].'.';
echo '</pre>';
}
}
?>