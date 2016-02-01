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
				die("ERRO: Id do post não pode ser igual a 0 / Lib.php (linha 18).");
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
		 * retorna id da pagina pela slug
		 */
		public static function getId($slug){
			$page = get_page_by_path($slug);
			return $page->ID;
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

		
	}

?>