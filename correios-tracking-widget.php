<?php
/*
Plugin Name: Correios Tracking Widget
Plugin URI: /
Description: Add a tracking widget for product shipped with Correios (Brazil postal office)
Author: Felipe Matos MOreira
Version: 1
Author URI: http://felipematos.com
*/
 
function CorreiosTrackingWidget()
{
    include_once 'correio.php';
    $code = @$_REQUEST['code'];
    
    //variável com o formulário de rastreio que será exibido no widget
    $form_rastreio = '<!--<h1>Rastreamento</h1>-->
                        <form>
                            <fieldset><legend>Pesquisar</legend>
                            <p><label>Código para rastreamento:</label> <input type="text" size="14" maxlength="13" name="code" value="'. (isset($code) ? $code : "") .'" />
                            <button>Pesquisar!</button>
                            </fieldset>
                        </form>';
    
    
    //verificar se o código de rastreio foi fornecido
    if (isset($code)){
        //cria o objeto para rastreio, e faz a requisição para o site dos correios passando o código fornecido
        $c = new Correio($code);
        //se não houve nenhum erro na requisição, constroi a tabela com os dados de rastreio
        if (!$c->erro){
            $tabela = '<h3 style=\"font:bold 8pt Tahoma;color:#000000;\">Código de rastreio: '. $code.'</h3>
                       <h3 style=\"font:bold 8pt Tahoma;color:#000000;\">Status: '. $c->status .'</h3>
                        <table style=\'font-size14px;\' border=1 width=\'100%\'>
                            <tr style=\"font:bold 8pt Tahoma;color:#CC0000;\">
                                <td  >Data</td>
                                <td>Local</td>
                                <td>Ação</td>
                                <td>Detalhes</td>
                            </tr>';
            foreach ($c->track as $l){
              $tabela .= '<tr style=\'font:8pt Tahoma; color=Navy\'>
                                <td nowrap=\'nowrap\'>'. trim($l->data) .'</td>
                                <td>'. trim($l->local) .'</td>
                                <td nowrap=\'nowrap\'>'. trim($l->acao) .'</td>
                                <td>'. trim($l->detalhes) .'</td>
                                <font>
                            </tr>';
            }
            $tabela .= '</table>';
        } else {
            $tabela = $c->erro_msg;
        }
        
        //cria variável com o código javascript a ser executado
        $js_codigo = '<script type="text/javascript">
                            function exitpop(){
                                my_window = window.open("", "mywindow1", "status=1,width=500,height=300");
                                my_window.document.write("<body bgcolor=\'#d8e6ed\'>'.str_replace(array("\n","\r"),"",$tabela).'</body>");                    
                            }
                            exitpop();
                       </script>';
        echo $js_codigo;
    }

    echo $form_rastreio;
}
 
function widget_CorreiosTracking($args) {
  extract($args);
  echo $before_widget;
  echo $before_title;?>Correios Tracking<?php echo $after_title;
  CorreiosTrackingWidget();
  echo $after_widget;
}
 
function CorreiosTrackingWidget_init()
{
  register_sidebar_widget(__('Correios Tracking'), 'widget_CorreiosTracking');
}
add_action("plugins_loaded", "CorreiosTrackingWidget_init");
?>