<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB; 

class Usuario extends Model
{
    protected $table = 'cao_usuario';
    public $timestamps = false;
    
   /* 
        SELECT usuario.co_usuario, usuario.no_usuario
        FROM agencedev.cao_usuario usuario
        left join agencedev.permissao_sistema permissao on(usuario.co_usuario = permissao.co_usuario)
        WHERE permissao.co_sistema = 1 
        AND permissao.in_ativo = 'S'
        AND permissao.co_tipo_usuario in (0,1, 2);
    */
    static function getListaUsuarioPermiso() {
        $usuarios = DB::table('cao_usuario')
                ->join('permissao_sistema', 'cao_usuario.co_usuario', '=', 'permissao_sistema.co_usuario')
                ->select('cao_usuario.co_usuario', 'cao_usuario.no_usuario')
                ->where('permissao_sistema.co_sistema', '=', 1)
                ->where('permissao_sistema.in_ativo', '=', 'S')
                ->whereIn('permissao_sistema.co_tipo_usuario', array(0, 1, 2))
                ->orderBy('cao_usuario.co_usuario')
                ->get();
        return $usuarios;        
    }
}