<?php
	/*
	 * Biblioteca de funcoes uteis da aplicacao. 
	 */
	class Lib{

		public static $themeUrl;

		/*
		 * retorna slug da pagina com id do parametro OU slug da pagina atual.
		 * OBS: ID do post NÃO pode ser igual a 0.
		 */
		public static function getSlug($id = null){
			if ($id == null) {
				global $post;
				$slug = get_post( $post )->post_name;
				return $slug;
			}elseif($id == 0) {
				die("ERRO: Id do post não pode ser igual a 0 / em Lib.php (linha 18).");
				//OBS: melhorar o tratamento deste erro...
			}else{
				$post = get_post($id);
				return $post->post_name;
			}
		}

		/*
		 * Incrementa a classe da página atual
		 */
		public static function getPageClass(){
			$class = 'page-' . Lib::getSlug();
			if ( is_home() ) {$class .= ' home';}
			if ( is_page() ) {$class .= ' page inner';}
			if ( is_404() ) {$class .= ' page-404 inner';}
			if ( is_single() ) {$class .= ' single inner';}
			if ( is_singular() ) {$class .= ' singular inner';}
			if ( is_archive() ) {$class .= ' archive inner';}
			
			return $class;
		}

		/*
		 * Disparo de Email
		 * $data = array(
		 *		//
		 *		//
		 *		//
		 *		//
		 * );
		 */
		function sendMail($data){
			//phpMailer
			require_once ('./././wp-includes/class-phpmailer.php');
			require_once ('./././wp-includes/class-smtp.php');

			//dados
			$name       = $data["name"];
			$mail       = $data["mail"];
			$subject    = $data["subject"];
			$content    = $data["message"];
			$host 		= $data["host"]; // servidor do email
			$port 		= $data["port"]; // porta do email
			$secure 	= $data["secure"]; // Set the encryption system to use - ssl (deprecated) or tls // geralmente = 'tls'
			$user 		= $data["user"]; //Username to use for SMTP authentication - use full email address for gmail
			$pass 		= $data["pass"]; //Password to use for SMTP authentication
			$fromMail 	= $data["fromMail"]; //Set who the message is to be sent from
			$fromDesc 	= $data["fromDesc"]; //Set who the message is to be sent from
			$replyMail 	= $data["replyMail"]; //Set an alternative reply-to address
			$replyDesc 	= $data["replyDesc"]; //Set an alternative reply-to address
			$sendToMail = $data["sendToMail"]; //Set who the message is to be sent to

			//Create a new PHPMailer instance
		    $mail = new PHPMailer;

		    //Tell PHPMailer to use SMTP
		    $mail->isSMTP();
		    
		    //Enable SMTP debugging
		    // 0 = off (for production use)
		    // 1 = client messages
		    // 2 = client and server messages
		    $mail->SMTPDebug = 0;
		    $mail->Debugoutput = 'html';
		    $mail->Host = $host;
		    $mail->Port = $port;
		    $mail->SMTPSecure = $secure;
		    $mail->SMTPAuth = true;
		    $mail->Username = $user;
		    $mail->Password = $pass;
		    $mail->setFrom( $fromMail , $fromDesc );
		    $mail->addReplyTo( $replyMail , $replyDesc );		    
		    $mail->addAddress( $sendToMail , $sendToDesc);
		    $mail->Subject = $subject;

		    //Read an HTML message body from an external file, convert referenced images to embedded,
		    //convert HTML into a basic plain-text alternative body
		    //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
		    $mail->msgHTML( $content );
		    
		    //Replace the plain text body with one created manually
		    $mail->Body = $content;
		    $mail->AltBody = $content;
		    
		    //send the message, check for errors
		    if (!$mail->send()) {
		        return $mail->ErrorInfo;
		    } else {
		        return true;		    }
		}


		/*
		 * Disparo de Email
		 * $data = array(
		 *		//
		 *		//
		 *		//
		 *		//
		 * );
		 */
		function sendContactMail($data){
			//phpMailer
			require_once ('./././wp-includes/class-phpmailer.php');
			require_once ('./././wp-includes/class-smtp.php');

			//disparo
			include "../includes/send-mail.php";
		}

		/*
		 * retorna id da pagina pela slug
		 */
		public static function getId($slug){
			$page = get_page_by_path($slug);
			return $page->ID;
		}

		/*
		 * retorna id da pagina pela slug
		 * @args = array{
		 *		"post" => post atual ($post) -> correcao do escopo do metodo
		 *		"maxFinalChar" => máximo de carcateres no ultimo item
		 *		"maxChar" => máximo de carcateres em todos os itens
		 *		"limitString" => texto a ser usado no final dos textos limitados
		 * }
		 */
		public static function getBreadcrumb($args){

			/*
			 * Dados
			 */
			$post = $args["post"];
			if ( isset($args["maxFinalChar"]) ) {
				$maxFinalChar = $args["maxFinalChar"];
			}
			if ( isset($args["maxChar"]) ) {
				$maxChar = $args["maxChar"];
			}
			if ( isset($args["limitString"]) ) {
				$limitString = $args["limitString"];
			}

			/*
			 * Gera o array
			 */
			include "../includes/breadcrumb.php";
			// include "/wp-content/plugins/z-toolkit/includes/breadcrumb.php";

			/*
			 * $b[] = array {
			 *		'txt' => texto do item,
			 *		'link' => link do item,	
			 * }
			 */
			return $b;
		}

		/*
		 * retorna o titulo do custom post
		 */
		public static function getCustomPostTitle($slug){
			$type = get_post_type_object($slug);
			return $type->labels->menu_name;
		}

		/*
		 * retorna dados do custom post
		 */
		public static function getPostType($slug){
			$type = get_post_type_object($slug);
			return $type->labels;
		}

		/*
	     * Resumo de todos os posts de um unico custom post
	     */
	    public static function getResumePostsByType($postType){

	        // filtro de posts
	        $args = array('post_type' => $postType, 'posts_per_page' => -1);
	        $posts = get_posts($args);

	        // vetor alvo
	        $articles = array();

	        // salva todos os produtos no vetor alvo
	        foreach($posts as $post): setup_postdata($post);
	            $id = $post->ID;
	            $articles[] = array(
	                'title' => get_the_title($id),
	                'slug' => $post->post_name,
	                'img' => wp_get_attachment_url( get_post_thumbnail_id($id) ),
	                // 'excerpt' => get_the_excerpt() ,
	                'excerpt' => $post->post_excerpt ,
	                // 'excerpt' => substr( get_the_excerpt() , 0, 50 ),
	                'link' => get_permalink( $id ),
	                'content' => get_the_content()
	            );
	        endforeach;

	        // retorna vetor
	        return $articles;
	    }

		/*
		 * pega todas as paginas filhas da pagina com a slug passada
		 */
		public static function getTheChildPages($slug){
			$args = array('post_type' => 'page', 'posts_per_page' => -1);
	        $pages = get_posts($args);
			foreach ($pages as $page) {
	            $parentId = $page->post_parent;
	            if ($parentId != 0) {
	                $parentSlug = Lib::getSlug($parentId);
	                if ($parentSlug == $slug) {
	                    $childPages[] = $page;
	                }
	            }
	        }

	        return $childPages;
		}

		/*
		 * pega todas os posts filhos do post com a slug passada
		 */
		public static function getTheAttachments($slug){
			$args = array('post_type' => 'attachment', 'posts_per_page' => -1);
	        $pages = get_posts($args);
			foreach ($pages as $page) {
	            $parentId = $page->post_parent;
	            if ($parentId != 0) {
	                $parentSlug = Lib::getSlug($parentId);
	                if ($parentSlug == $slug) {
	                    $childPages[] = $page;
	                }
	            }
	        }

	        return $childPages;
		}

		/*
		 * Forma simples de criar novos Custom Post Types
		 	$args = array(
				'slug' => "string",
				'title' => "string",
				'pluralName' => "string",
				'singleName' => "string",
				'genreName' => "male OR famale",
			    'supports' => array(
			    	'item 1',
			    	'item 2'
			    )
			);
		 */
		public static function createPostType($args){

			$data = $args;

			/**
			 * Criar custom post type
			 */
			function newPostType($data) {

				echo $data;
				var_dump($data);

				/**
			     * dados
			     */
				$slug = $data['slug'];
				$title = $data['title'];
				$pluralName = $data['pluralName'];
				$singleName = $data['singleName'];
				$genreName = $data['genreName'];
			    $supports =$data['supports'];

			    /**
			     * Define gênero (artigo A ou O)
			     */
				if ($genre == "male") {
					$article = array(
						'upper' => 'O', 
						'lower' => 'o'
					);
				}else{
					$article = array(
						'upper' => 'A',
						'lower' => 'a'
					);
				}

			    /**
			     * Labels customizados
			     */
			    $labels = array(
					'name' => _x( $singleName, 'post type general name'),
					'singular_name' => _x($pluralName, 'post type singular name'),
					'add_new' => _x('Nov' . $article['lower'] . ' ' . $singleName, 'Novo item'),
					'add_new_item' => __('Nov' . $article['lower'] . ' ' . $singleName),
					'edit_item' => __('Editar ' . $singleName),
					'new_item' => __('Nov' . $article['lower'] . ' ' . $singleName),
					'view_item' => __('Visualizar ' . $singleName),
					'search_items' => __('Procurar ' . $singleName),
					'not_found' =>  __('Nenhuma ' . $singleName . ' encontrada'),
					'not_found_in_trash' => __('Nenhum ' . $singleName . ' encontrado na lixeira'),
					'parent_item_colon' => '',
					'menu_name' => $title
			    );

			    /**
			     * Configuracoes
			     */
			    $rewrite = array(
					'slug' => $slug,
					'with_front' => false,
			    );
			    
			    /**
			     * Registrar o tipo de post
			     */
			    register_post_type( $slug, array(
				    'labels' => $labels,
				    'public' => true,
				    'publicly_queryable' => true,
				    'show_ui' => true,
					'menu_icon' => 'dashicons-edit',
				    'show_in_menu' => true,
				    'has_archive' => $slug,
				    'rewrite' => $rewrite,
				    'capability_type' => 'post',
				    'has_archive' => true,
				    'hierarchical' => false,
				    'menu_position' => null,
				    'supports' => $supports
				    )
			    );
			    
			}

			/**
			 * Hook de inicio do wordpress
			 */
			add_action( 'init', 'newPostType' );

		}

		/*
		 * debug da variavel passada no param
		 */
		public static function debug($var){
			echo '<br/><br/><br/><pre>';
            var_dump($var);
            echo '</pre><br/><br/><br/>';
		}

		/*
		 * testa se arquivo existe
		 */
		public static function testFile($file){
	        if (!file_exists($file)) {
	            return false;
	        }
			return true;
	    }

	    /*
		 * Retornar mediaPosts em uma única galeria do post
		 */
		public static function getTheImagesGallery($post){
			$targetString = $post->post_content;
			$targetString = str_replace('[gallery ids="', "", $targetString);
			$targetString = str_replace('"]', "", $targetString);
			$idList = explode(",", $targetString);
			foreach ($idList as $id) {
				$imgs[] = get_post($id);
			};
			return $imgs;
	    }

	    /*
	     * Validar o Google Recaptcha
	     */
	    public static function captchaValidate($privatekey){
	    	include "/wp-content/plugins/z-toolkit/vendor/google-recaptcha/recaptchalib.php";
		    $response = null;
		    $reCaptcha = new ReCaptcha($privatekey);

		    /*
		     * se submetido, verifica o formulario
		     */
		    if ( isset($_POST["data"]["captcha"] ) ) {
		        //valida o captcha
		        $response = $reCaptcha->verifyResponse(
		            $_SERVER["REMOTE_ADDR"],
		            $_POST["data"]["captcha"]
		        );
		        // retorna resultado
		        if ($response != null && $response->success) {
		            return true;
		        }else{
		            return false;
		        }
		    }else{
		    	// retorna resultado
		        return false;
		    }
	    }
		
	}

?>