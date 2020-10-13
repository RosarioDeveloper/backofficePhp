<?php
require APP_URL."vendor/autoload.php";
require_once APP_URL."src/shared/errors.php";
require_once APP_URL."assets/PHPMailer/vendor/autoload.php";
require_once APP_URL.'assets/dompdf/autoload.inc.php';

ob_start();
use \Firebase\JWT\JWT;
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Scripts extends Errors {

	public function generateToken($params){
		$payload = array(
			$params,
			"exp" => time() + 1000,
		);
		$jwt = JWT::encode($payload, SECRET_KEY);
		return $jwt;
	}

	//SCRIPT CALENDAR
	public function calendar($type, $index){
		$this->shorMonth = array(
		    "01"=>"Jan", "02"=>"Fev", "03"=>"Mar", "04"=>"Abr", "05"=>"Mai", "06"=>"Jun",
		    "07"=>"Jul", "08"=>"Agos", "09"=>"Set", "10"=>"Out", "11"=>"Nov", "12"=>"Dez"
		);

		if($type == "shortMonth"){
			return $this->shorMonth[$index];
		}
	}

	//REMOVE SPECIAL CHARS SCRIPT
	public function clear_str($str){
		$search = array("/", " ", "_");
		$replace = array("","-","-");

		$string = preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $str ));
		return  str_replace($search , $replace, strtolower($string));
	}

	public function clear_struuu($str){
		$acentos = array(' ','à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô',
		'õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ',
		'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý','""','?',':','/','&#34;','“','”','&');

		$sem_acentos = array('-','a','a','a','a','a','c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o',
		'o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I',
		'N', 'O','O','O','O','O', 'U','U','U','U', 'Y', '','','','-','','','','');

	   //$string = str_replace($acentos, $sem_acentos, filter_var(strtolower($str), FILTER_SANITIZE_STRING));
	   return preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $str ));
		//return  $string;
	}

	//FORMAT NUMBER SCRIPT
	public function price_format($num)
	{
		$serach = array(".",","); $replace = array("",".");
		$number = str_replace($serach, $replace, filter_var($num, FILTER_SANITIZE_STRING));
		return $number;
	}

	//VAR FILTER SCRIPT
	public function var_filter($str, $tipo_filter)
	{
		if($tipo_filter == "string"){$this->str = filter_var($str, FILTER_SANITIZE_STRING);}
		if($tipo_filter == "int"){$this->str = filter_var($str, FILTER_SANITIZE_NUMBER_INT);}
		if($tipo_filter == "float"){$this->str = filter_var($str, FILTER_SANITIZE_NUMBER_FLOAT);}
		return $this->str;
	}

	//FILTER ARRAY
	public function special_chars_array($array){
		$newArray = [];
		foreach ($array as $key => $item) {
			$newArray[$key] = filter_var($item, FILTER_SANITIZE_STRING);
		}
		return $newArray;
	}

	//Gerador de cores
	public function color_change(){
		$this->input = array("gd-warning", "gd-info", "gd-primary", "gd-danger", "gd-success","gd-dark","gd-secondary");
		$this->rand_keys = array_rand($this->input, 2);
		//echo $input[$rand_keys[0]] . "\n";
		return $this->input[$this->rand_keys[0]];
	}

	//Formato de data
	public function format_data($data){
		$date = new DateTime($data);
		return $date->format("d-m-Y");
	}

	//Intervalo de data
	public function intervalo_data($data_in, $data_en){
		$this->data_in = new DateTime($data_in);
		$this->data_en = new DateTime($data_en);
		$this->intervalo = $this->data_in->diff($this->data_en);
		return $this->intervalo->d;
	}

	//verifica capatcha
	public function captcha_verif($secret_key, $response){
		if(isset($response) && $response != "")
		{
			$url = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.
        	'&response='.$response));
			if($url->success == true){
				$this->captcha_msg = "true";
			}else{
				$this->captcha_msg = "Por favor faça a verificação da caixa de seleção";
			}
		}else{
			$this->captcha_msg = "Por favor faça a verificação da caixa de seleção";
		}
	}

	//Api SMS
	public function api_sms($tlf, $msg){
		for ($i=0; $i <count(array_filter($tlf)) ; $i++) {
			$url = 'https://netsms.co.ao/app/appi/?accao=enviar_sms&chave_entidade=56Ss66H5gTFde65dfE2c5Ys2JeK&destinatario='.$tlf[$i].'&descricao_sms='.urlencode(nl2br($msg[$i])).'';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			$result = curl_exec($ch);
		}

		$this->api_msg = json_decode($result);
		//print_r($this->api_msg);
	}


	//EMAIL SCRIPTS
	public function send_email($array){
		try {
			$mail = new PHPMailer(true);
			$mail->isSMTP();                                                            // Set mailer to use SMTP
			$mail->Host = 'mail.netsms.co.ao';                                // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                                                     // Enable SMTP authentication
			$mail->Username = 'info@netsms.co.ao';                           // SMTP username
			$mail->Password = '8PbEYpD]#8ki';                                             // SMTP password
			$mail->SMTPSecure = 'ssl';                                                  // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 465;
			//Content
			$mail->isHTML(true);                                                     // TCP port to connect to

			//Recipients
			$mail->setFrom($array['from'], $array['remitente']);
			//$mail->addAddress($array['email'][0]);
			foreach($array['email'] as $key => $email){
				isset($array['replyTo']) ? $mail->AddReplyTo($array['replyTo'], $email) : false;
				$mail->addAddress($email);
				//$mail->AddBCC($email);
			}

			if(isset($array['anexo']) && count($array['anexo']) != 0){
				foreach ($array['anexo'] as $key => $file) {
					$mail->addAttachment($file);
				}
			}

			$mail->Subject = utf8_decode($array['assunto']);
			$mail->Body    = utf8_decode($array['mensagem']);

			if($mail->send()){
				$this->msg_mail = true;
			}else{
			 	$this->msg_mail = false;
			}
		}catch (Exception $e){
			$this->msg_mail = false;
		}
	}

	//SCRIPT PDF CREATOR
	public function pdf_creator($file){
		$options = new Options();
		$options->set('isRemoteEnabled', TRUE);
		$dompdf = new Dompdf($options);

		$context = stream_context_create([
			'ssl' => [
				'verify_peer' => FALSE,
				'verify_peer_name' => FALSE,
				'allow_self_signed'=> TRUE
			]
		]);
		$dompdf->setHttpContext($context);

		//$html = ob_get_clean();
		$html = $file['html'];

		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		$output = $dompdf->output();
		$this->pdf_status = file_put_contents($file['path'].$file['name'], $output) ? true : false;
   }

   public function httpBuild($options){
      $httpQuery = http_build_query($options);
		$contextOpts = array("http" => array(
			'method'  => 'POST',
			'header'  => 'Content-Type: application/x-www-form-urlencoded',
			'content' => $httpQuery
		));
		return stream_context_create($contextOpts);
   }


	//==================================== UPLOAD ====================================
	public $upload = [];
	public function upload($file, $mime_types, $path)
	{
		$this->validate($file, $mime_types);
		if($this->upload['status'] == "sucesso"){
			$this->move_file($file, $path);
		}
	}

	//Erro de arquivos
	public function validate($file, $mime_types)
	{
		$default_size = 2*1024*1024; //2Mb

		//Verifica o tamanho
		switch($file)
		{
			case $default_size < $file['size']:
				$this->upload['status'] = "erro";
				$this->upload['msg'] =  "Arquivo muito grande. 2MB no máximo.";
				break;

			case !in_array(strtolower($file['type']), $mime_types):
				$this->upload['status'] = "erro";
				$this->upload['msg'] =  "Arquivo não suportado";
				break;

			default:
				$this->upload['status'] = "sucesso";
				break;
		}
	}

	public function move_file($file, $path)
	{
		if(is_dir($path) &&  move_uploaded_file($file['tmp_name'], $path.$this->clear_str($file['name'])))
		{
			$this->upload['status'] = "sucesso";
			$this->upload['file_name'] = $this->clear_str($file['name']);
			$this->upload['type'] = $file['type'];
			$this->upload['path'] = $path;
		}else{
			$this->upload['status'] = "erro";
			$this->upload['msg'] = "Erro ao fazer o upload do ficheiro: ".$file['name'];
		}
	}
}




?>
