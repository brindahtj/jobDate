<?php

namespace App\Services;

use Throwable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UploadFilesServices extends AbstractController
{
    private  function generateUniqueFilename()
    {
        $name = bin2hex(random_bytes(16)) . uniqid();
        return $name;
    }
    public function saveFileUpload($file)
    {
        //recuperation du nom du fichier 
        $fileName = $file->getClientOriginalName();
        // generation d'un nom unique pour le fichier 
        $fileName = $this->generateUniqueFilename() . '.' . $file->guessExtension();
        $file->move(
            $this->getParameter('uploads_directory'),
            $fileName
        );
        return $fileName;
    }

    public function updateFileUpload($file, $oldFile)
    {
        $fileName = $this->saveFileUpload($file);
        if ($oldFile !== 'default.png') {
            unlink($this->getParameter('uploads_directory') . '/' . $oldFile);
        }
        return $fileName;
    }

    // Méthode permettant de supprimer un fichier uploadé dans le dossier public/uploads
    public function deleteFileUpload($file)
    {
        try {
            // Suppression de l'ancien fichier si il existe et si ce n'est pas le fichier par défaut
            if ($file != 'default.png') {
                unlink($this->getParameter('uploads_directory') . '/' . $file);
            }
        } catch (Throwable $th) {
            // Si le fichier n'existe pas, on ne fait rien
        }
    }
}
