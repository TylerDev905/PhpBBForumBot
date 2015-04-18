<?php
include("request.php");

class forumBot{
	
	public $domain;
	public $username;
	public $password;
	public $email;
	public $subject;
	public $msg;
	public $mode;
	public $forumId;
	public $threadId;
	public $postId;
	public $groupId;
	public $userId;
	public $sig;
	public $token;
	public $data;
	public $youtube;
	public $items;
	public $link;
	public $columns;
	public $page;
	public $status;
	public $index;
	
	/*login a user to the forum*/
	function login(){
		$request = new request();
		
		$fields = array( 
						'username' => $this->username,
						'password' => $this->password,
						'login' => 'Login',
						'redirect' => './index.php'
				);
				
		$request->post($this->domain."ucp.php?mode=login", $fields, $this->username);
		$this->token = $request->parse('index.php?sid=','" />');
		$this->status = $this->username.': '.$request->parse("<h2>Information</h2>\n\t<p>","<br /><br />");
	}
	
	/*login a user to the admin panel*/
	function loginAdmin(){
		$request = new request();
		
		$url = $this->domain.'adm/index.php?sid='.$this->token;
		$request->get($url, $this->username);
		$hash = $request->parse('name="credential" value="','" />');
		
		$fields = array( 
						'username' => $this->username,
						'password_'.$hash => $this->password,
						'redirect' => './../adm/index.php?sid='.$this->token,
						'sid' => $this->token,
						'credential' => $hash,
						'login' => 'Login'
				);
				
		$request->post($url, $fields, $this->username);
		$this->token = $request->parse('index.php?sid=','" />');
		$this->status = $this->username.': '.$request->parse("<h2>Information</h2>\n\t<p>","<br /><br />");
		
		$request->get($this->domain.'adm/index.php?sid='.$this->token, $this->username);
		
	}
	
	/*logout a user*/
	function logout(){
		$request = new request();
		$request->get($this->domain.'ucp.php?mode=logout&sid='.$this->token, $this->username);
		$this->status = $this->username.': '."You have been successfully logged out.";
		
	}
	
	function idle(){
		$request = new request();
		$request->get($this->domain.$this->link, $this->username);
		$request->dump('idle.html');
		$this->status = $this->username.": is idling on ".$this->link;
	}
	
	/*register a user through the registration form*/
	function register(){
		$request = new request();
		
		$request->get($this->domain.'ucp.php?mode=register', $this->username);
		$creation = $request->parse('"creation_time" value="','" />');
		$formToken = $request->parse('"form_token" value="','" />');
		
		
		
		/*Agreement Accept*/
		$fields = array(
						'agreed' 			=>	'I agree to these terms',
						'change_lang' 		=>	'',
						'creation_time'		=>	$creation,
						'form_token' 		=>	$formToken	
					);
					
		$request->post($this->domain.'ucp.php?mode=register',$fields, $this->username);			
		$this->token = $request->parse('"sid" value="','" />');
		$creation = $request->parse('"creation_time" value="','" />');
		$formToken = $request->parse('"form_token" value="','" />');
		
		sleep(2);
		
		
		
		/*Registraton form*/
		$fields = array(
						'username' 			=> 	$this->username,
						'email' 			=> 	$this->email,
						'email_confirm' 	=> 	$this->email,
						'new_password' 		=> 	$this->password,
						'password_confirm' 	=> 	$this->password,
						'lang' 				=> 	'en',
						'tz' 				=> 	'-5',
						'agreed' 			=> 	'true',
						'change_lang' 		=> 	'0',
						'submit' 			=> 	'Submit',
						'creation_time' 	=> 	$creation,
						'form_token' 		=> 	$formToken
					);
		
		$request->post($this->domain.'ucp.php?mode=register&sid='.$this->token,$fields, $this->username);
		$this->status = $request->parse("<h2>Information</h2>\n\t<p>","<br /><br />");
		
	}
	
	/*post/edit a thread or post */
	function posting(){
		$request = new request();
		switch($this->mode){
			case"post":$url = $this->domain."posting.php?mode=".$this->mode."&f=".$this->forumId."&sid=".$this->token;
				break;
			case"reply":$url = $this->domain."posting.php?mode=".$this->mode."&f=".$this->forumId."&t=".$this->threadId."&sid=".$this->token;
				break;
			case"edit":$url = $this->domain."posting.php?mode=".$mode."&f=".$this->forumId."&p=".$this->postId."&sid=".$this->token;
				break;
			default:exit("Error: Posting[mode] was not set correctly");
				break;
		}
		
		$request->get($url, $this->username);
		$lastClick = $request->parse('"lastclick" value="', '" />');
		$creation = $request->parse('"creation_time" value="','" />');
		$formToken = $request->parse('"form_token" value="','" />');
		
		sleep(2);
		
		$fields = array(	
					'icon'				=> '0',
					'subject'			=> $this->subject,
					'addbbcode20'		=> '100',
					'message' 			=> $this->msg,
					'lastclick'			=> $lastClick,
					'post' 				=> 'Submit',
					'attach_sig' 		=> 'on',
					'topic_type' 		=> '0',
					'topic_time_limit' 	=> '0',
					'creation_time' 	=> $creation,
					'form_token' 		=> $formToken,
					'filecomment' 		=> '',
					'poll_title' 		=> '',
					'poll_option_text' 	=> '',
					'poll_max_options' 	=> '1',
					'poll_length' 		=> '0'
				);
				
		$request->post($url, $fields, $this->username);
		$this->status = $request->parse("<h2>Information</h2>\n\t<p>","<br /><br />");
		
	}
	
	/*Send a personal Message to a group ids and user ids*/
	function personalMsg(){
		$request = new request();
		
		$url =  $this->domain.'ucp.php?i=pm&mode=compose';
		$request->get($url, $this->username);
		$lastClick = $request->parse('"lastclick" value="', '" />');
		$creation = $request->parse('"creation_time" value="','" />');
		$formToken = $request->parse('"form_token" value="','" />');
		
		
		sleep(2);
		
		$fields = array(
					'username_list' => '',
					'icon' => '0',
					'subject' => $this->subject,
					'addbbcode20' => '100',
					'message' => $this->msg,
					'last_click' => $lastClick,
					'status_switch' => '0',
					'post' => 'Submit',
					'attach_sig' => 'on',
					'creation_time' => $creation,
					'form_token' => $formToken
				);
				
		if(isset($this->groupId)){
			foreach($this->groupId as $id){
				$fields['address_list[g]['.$id.']'] = 'to';
			}
		}
		
		if(isset($this->userId)){
			foreach($this->userId as $id){
				$fields['address_list[u]['.$id.']'] = 'to';
			}
		}
		
		$request->post($url.'&action=post&sid='.$this->token, $fields, $this->username);
		
	}
	
	function signature(){
		$request = new request();
		
		$url =  $this->domain.'ucp.php?i=profile&mode=signature&sid='.$this->token;
		$request->get($url, $this->username);
		$creation = $request->parse('"creation_time" value="','" />');
		$formToken = $request->parse('"form_token" value="','" />');
		
		
		sleep(2);
		
		$fields = array(
					'addbbcode20' => '100',
					'signature' => $this->sig,
					'submit' => 'Submit',
					'creation_time' => $creation,
					'form_token' => $formToken
				);
				
		$request->post($url, $fields, $this->username);
	}
	
	function youtube(){
		$request = new request();
		$cache = getcwd()."\\tmp\\youtube\\".$this->youtubeName.'.txt';
		
		$url = "http://gdata.youtube.com/feeds/api/users/".$this->youtubeName."/uploads?v=2&alt=jsonc&max-results=".$this->items;
		$request->get($url);
		$new = json_decode($request->page);
		
		if(file_exists($cache)){
			$old = json_decode(file_get_contents($cache));
			if(isset($old->data->items[0]->id) && $old->data->items[0]->id == $new->data->items[0]->id){
				//$this->status = $this->username.': has already posted these items from '.$this->youtubeName."'s youtube channel.";
				//exit(json_encode($this));
				exit();
			}
		}
		file_put_contents($cache, $request->page);
		
		foreach($new->data->items as $item){
			$this->mode = "post";
			$this->subject = $item->title;
			$this->msg = "[youtube]".$item->id."[/youtube]\n[b]Description:[/b]\n".$item->description;
			$this->posting();
			sleep(2);
		}
		$this->status = $this->username.': posted['.$this->items.'] items from '.$this->youtubeName."'s youtube channel.";
	}
	
	function viral(){
		$request = new request();
		$url = "http://imgur.com/gallery/hot/viral/day/page/0/hit?scrolled&set=".$this->page;
		$request->get($url);
		$img = $request->parse('<img alt="" src="','" title', FALSE);
		$id = $request->parse('<div id="','" class="post">', FALSE);
		array_pop($id);
		
		$cache = getcwd()."\\tmp\\viral\\viral".$this->page.".txt";
		if(file_exists($cache)){
			$date = file_get_contents($cache);
			if($date == date("j/n/Y")){
				//$this->status = $this->username.': Has already posted page['.$this->page.'] of viral images today.';
				//exit(json_encode($this));
				exit();
			}
			else
				file_put_contents($cache, date("j/n/Y"));
		}
		else{
			file_put_contents($cache, date("j/n/Y"));
		}
		
		$col = 1;
		$msg = "";
		for($i = 0; $i < count($img); $i++){
			if($this->columns == $col){
				$msg .= '[url=http://imgur.com/gallery/http:'.$id[$i].'][img]http:'.$img[$i].'[/img][/url]'."\n";
				$col = 1;
			}	
			else{
				$msg .= '[url=http://imgur.com/gallery/http:'.$id[$i].'][img]http:'.$img[$i].'[/img][/url]';
				$col++;
			}
		}
		$this->mode = "post";
		$this->subject = "Viral Images for ".date("j/n/Y") . " page: ".$this->page;
		$this->msg = $msg;
		$this->posting();
		$this->status = $this->username." posted viral images for ".date("j/n/Y") . " page: ".$this->page;
	}
}
	
	/*If there are any GET variables present and a GET variable 'action' must be set*/
	if(count($_GET) > 0 && isset($_GET['action'])){
		
		/*create new forum bot object*/
		$bot = new forumBot();
		
		/*serialize forum bot's properties*/
		foreach(array_keys($_GET) as $var){
			$bot->{$var} = $_GET[$var];
		}
		/*execute the action*/
		$bot->{$bot->action}();
		
		/*convert the bots object to json then display*/
		echo json_encode($bot);
	}
	



?>
