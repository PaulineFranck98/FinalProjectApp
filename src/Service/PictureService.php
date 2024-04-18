<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '')
    {
        // On donne un nouveau nom à l'image
        $file = md5(uniqid(rand(), true)) . '.webp';

        // On récupère les infos de l'image
        $picture_infos = getimagesize($picture);

        if($picture_infos === false) {
            // Gérer l'exception
            throw new Exception('Format d\'image incorrect');
        }

        // On vérifie le format de l'image
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

       

        // Je définis le chemin
        $path = $this->params->get('images_directory') . $folder;

        // On crée le dossier de destination s'il n'existe pas
        // Un dossier 'mini' contiendra les images resized
        // Si le dossier n'existe pas
        if(!file_exists($path)){ // = /place/mini
            // 0755 : pour les permissions
            // $recursive = true --> si pas de parent, alors va tout créer : fait l'arborescence complète
            mkdir($path, 0755, true);
        }

        // On stocke l'image originake
        imagewebp($picture_source, $path . '/' .  $file);

        // C'est un fichier donc je peux le déplacer (move())
        // Je déplace le fichier dans mon path
        // J'ajoute le / pour éviter d'éventuels problèmes avec windows
        $picture->move($path . '/' , $file);
        // Je fais un return du fichier car je dois récupérer son nom
        return $file;

    }

    // On gère la suppression
    // On prend par défaut le file, le folder sera vide par défaut
    // On prend les dimensions pour savoir quelles dimensions on supprime
    public function delete(string $file, ?string $folder = '')
    {   
        // Je ne veux pas supprimer le fichier par défaut
        if($file !== 'default.webp'){
            // J'initialise $success à false pour dire si ça a fonctionné ou non
            $success = false;
            $path = $this->params->get('images_directory') . $folder;

    
            // chemin original
            $original = $path . '/' . $file;

            // Je vérifie si le fichier existe
            if(file_exists($original))
            {
                // Supprime l'original'
                unlink($original);
                $success = true;
            }
            // return true ou false si le unlink n'a pas fonctionné
            return $success;
        }

        return false;
    } 
}