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

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
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

        // On recadre l'image : $picture_infos va contenir la largeur et la hauteur
        // On récupère les dimensions
        $imageWidth = $picture_infos[0];
        $imageHeight = $picture_infos[1];

        // On vérifie l'orientation de l'image
        // On fait ici une triple comparaison : 
        // width < height, width = height, width > height
        // On obtient -1 , 0 ou 1
        switch($imageWidth <=> $imageHeight){
            case -1: // portrait
                // On positionne ici la découpe
                // On laisse x en pleine largeur, et on descend y à la moitié : enlève le haut et le bas en divisant par 2
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHeight - $squareSize) / 2;
                break;
            case 0: // carré
                // Pas besoin de positionner la découpe
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;
                break;
            case 1: // paysage
                // On positionne ici la découpe
                // On laisse y en pleine largeur, et on descend x à la moitié --> enlève le haut et le bas en divisant par 2
                $squareSize = $imageHeight;
                $src_x = ($imageWidth - $squareSize) / 2;
                $src_y = 0;
                break;

        }

        // On crée une nouvelle image "vierge" dans laquelle on va venir coller la découpe
        $resized_picture = imagecreatetruecolor($width, $height);

        imagecopyresampled($picture_source, $resized_picture, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);

        // Je définis le chemin
        $path = $this->params->get('images_directory') . $folder;

        // On crée le dossier de destination s'il n'existe pas
        // Un dossier 'mini' contiendra les images resized
        // Si le dossier n'existe pas
        if(!file_exists($path . '/mini/')){ // = /place/mini
            // 0755 : pour les permissions
            // $recursive = true --> si pas de parent, alors va tout créer : fait l'arborescence complète
            mkdir($path . '/mini/', 0755, true);
        }

        // On stocke l'image recadrée
        imagewebp($resized_picture, $path . '/mini/' . $width . 'x' . $height . '-' . $file);

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
    public function delete(string $file, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {   
        // Je ne veux pas supprimer le fichier par défaut
        if($file !== 'default.webp'){
            // J'initialise $success à false pour dire si ça a fonctionné ou non
            $success = false;
            $path = $this->params->get('images_directory') . $folder;

            // Me permets de savoir quel fichier je supprime
            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $file;

            // Je vérifie si le fichier existe
            if(file_exists($mini))
            {
                // Supprime la miniature
                unlink($mini);
                $success = true;
            }

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