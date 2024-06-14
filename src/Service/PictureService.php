<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

// Je définis une nouvelle classe nommée PictureService
class PictureService
{
    // Je définis une propriété $params
    private $params;

    // je définis le constructeur de la classe avec en argument :
    // l'objet ParameterBagInterface que j'assigne à la propriété $params
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    // Je définis une méthode nommée add() avec en premier argument l'objet UploadedFile 
    // Deuxième argument optionnel de type string, avec une valeur par défaut de chaîne vide
    public function add(UploadedFile $picture, ?string $folder = '')
    {
        // Je génère un nouveau nom de fichier unique en utilisant md5(), uniqid() et rand()
        // Je concatène l'extension de fichier '.webp' et je stocke le nom dans la variable $file
        $file = md5(uniqid(rand(), true)) . '.webp';

        //Je récupère les informations de l'image avec la méthode 'getimagesize'
        $picture_infos = getimagesize($picture);
        // Je vérifie si la variable $picture_infos est  égale à false
        if($picture_infos === false) {
            //Si c'est le cas, la fonction 'getimagesize' a échoué, je gère alors l'exception 
            throw new Exception('Format d\'image incorrect');
        }

        // On vérifie le format de l'image en utilisant la valeur mime de la variable $picture_infos
        switch($picture_infos['mime']){
            case'image/png':
                // Je récupère l'image dans une variable pour pouvoir la manipuler
                $picture_source = imagecreatefrompng($picture);
                break;
            case'image/jpeg':
                // Je récupère l'image dans une variable pour pouvoir la manipuler
                $picture_source = imagecreatefromjpeg($picture);
                break;
            case'image/webp':
                // Je récupère l'image dans une variable pour pouvoir la manipuler
                $picture_source = imagecreatefromwebp($picture);
                break;
                default : 
                    throw new Exception('Format d\'image incorrect');
        }

       

        // Je définis le chemin d'accès où l'image sera stockée et je le stocke dans la variable $path
        $path = $this->params->get('images_directory') . $folder;

        // Je vérifie si le chemin d'accès existe déjà en utilisant la méthode file_exists()
        if(!file_exists($path)){ 
            // J'utilise la méthode mkdir() pour créer un nouveau répertoire avec les permissions associées
            // 0755 : pour les permissions
            // $recursive = true --> si pas de parent, alors va tout créer : fait l'arborescence complète
            mkdir($path, 0755, true);
        }

        // J'utilise la fonction imagewebp() pour enregistrer l'image dans le chemin d'accès
        //  spécifié au format webp et j'utilise le nom de fichier unique généré 
        imagewebp($picture_source, $path . '/' .  $file);

        // Je déplace le fichier vers le chemin d'accès spécifié
        // J'ajoute le / pour éviter d'éventuels problèmes avec windows
        $picture->move($path . '/' , $file);
        // Je fais un return du fichier car je dois récupérer son nom
        return $file;

    }

    // Je définis une méthode nommée delete(), avec en premier argument le nom du fichier
    // Deuxième argument optionnel de type string avec une valeur par défaut de chaîne vide
    // On prend par défaut le file, le folder sera vide par défaut
    public function delete(string $file, ?string $folder = '')
    {   
        // J'initialise $success à false pour dire préciser ça a fonctionné ou non
        $success = false;
        $path = $this->params->get('images_directory') . $folder;

        // chemin original
        $original = $path . '/' . $file;

        // Je vérifie si le fichier existe
        if(file_exists($original))
        {
            // Supprime l'originaldu dossier
            unlink($original);
            $success = true;
        }
        // return true si le unlink a fonctionné 
        return $success;
        
    } 
}