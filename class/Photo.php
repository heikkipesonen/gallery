<?php
class Photo{

	public static function getSlug(){
		$slug = Util::generateRandomToken(6);
		
		$exists = ORM::for_table('client')->where('slug',$slug)->find_one();
		if (is_object($exists)){
			$slug = getSlug();
		}

		return $slug;
	}

	public static function deleteGallery($id, $slug){
		if (self::isAdmin()){			
			$client = ORM::for_table('client')->where('slug',$slug)->find_one();
			$gallery = ORM::for_table('gallery')->where('id',$id)->find_one();
			
			if (is_object($gallery) && is_object($client)){
				$clientPath = 'uploads/'.$client->slug;
				$folder = $clientPath.'/'.$gallery->id.'/';
				$result = ORM::for_table('photo')->where('client_id',$client->id)->where('gallery_id',$gallery->id)->delete();			

				if (file_exists($folder)){
					Util::deleteDir($folder);			
				}
				$gallery->delete();

				return true;
			}
		}

		return false;
	}

	public static function deleteClient($id,$deleteGalleries = true){
		if (self::isAdmin()){			
			$client = ORM::for_table('client')->where('id',$id)->find_one();

			if (is_object($client)){
				if ($deleteGalleries == true){
					$galleries = ORM::for_table('gallery')->where('client_id',$client->id)->find_many();

					foreach ($galleries as $gallery) {
						try{
							sself::deleteGallery($gallery->id, $cliet->slug);
						} catch (exception $e) {

						}
					}
				}

				$client->delete();
				return true;
			}
		}
		return false;
	}

	public static function deleteImage($id){
		$img = ORM::for_table('photo')->where('id',$id)->find_one();
		if (is_object($img)){
				$gallery = $img->gallery_id;
				$client = ORM::for_table('client')->where('id',$img->client_id)->find_one();

				if (is_object($client)){
					$clientPath = 'uploads/'.$client->slug;
					$folder = $clientPath.'/'.$gallery.'/';

					$filename = $img->file;
					$fileParts = explode('.',$filename);

					$files = glob($folder.$fileParts[0].'_*'.$fileParts[1]);

					if ($files){
						foreach ($files as $file) {
							unlink($file);
						}

						return true;
					}
				}
		}

		return false;
	}

	public static function isGalleryOwner($id){
		if (isset($_SESSION['auth'])){		

			$galleryOwner = ORM::for_table('gallery')->where('client_id',Util::alphaNum($_SESSION['auth']))->where('id',$id)->find_one();

			if (is_object($galleryOwner)){
				if ($_SESSION['lvl'] >= 3){
					return true;
				}
			}
		}
		return false;
	}
	
	public static function isLogin(){
		if (isset($_SESSION['lvl'])){
			return $_SESSION['lvl']>0;
		}
		return false;
	}

	public static function isAdmin(){
		if (isset($_SESSION['lvl'])){
			return $_SESSION['lvl'] == 10;
		}
		return false;
	}
/*
	public static function isAuthorized($id){
		if (self::isAdmin()) return true;
		if (self::isGalleryOwner($id)) return true;
		return false;
	}
*/
}
?>