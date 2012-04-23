<?php

class Default_Helper_PictureUri
{
    public function pictureUri($base64)
    {
        return 'data:image/jpg;base64,' . $base64;
    }
}