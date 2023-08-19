<?php

namespace App\Libs;

class MakePath {

    /**
     * @param int $id
     * @param $ext
     * @param $max_dir_depth 2 with cover, figure 3 with document file
     * @return string
     * @todo redirect depth 2 to depth 3 for images
     */
    public static function make(int $id, $ext = 'ext', $max_dir_depth = 3){
        $multiplier = ( $max_dir_depth + 1 ) * 3 - strlen( (string) $id );
        if($multiplier >= 0){
            $full = str_repeat( "0", $multiplier) . $id;
        }else{
            $full = (string)$id;
        }
        $full = substr( $full, 0, -3);
        $path_partials = [];
        for($i = 1; $i < $max_dir_depth; $i++){
            $path_partials[] = substr( $full, -3*$i, 3);
        }
        $path_partials[] = substr( $full, 0, strlen( $full ) - 3*($max_dir_depth-1) );
        $path_partials = array_reverse( $path_partials );
        return implode( "/", $path_partials ) . "/" . $id . ($ext ? "." . $ext : "");
    }

}
