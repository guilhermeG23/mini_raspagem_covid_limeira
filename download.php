<?php
#Fazer o download
function fazer_download() {
    #Caminho
    $caminho = "/usr/local/www/apache24/data/teste_covid/";
    #Dia atual
    $data = date('Y-m-d');
    #URL para acesso
    $url = "https://www.limeira.sp.gov.br/sitenovo/downloads/coronavirus/planilha_de_casos/";
    #Buscar o arquivo com base no DOM da pagina e colocar em TXT
    $saida_curl = shell_exec("curl {$url} | grep {$data}");
    #Confere se ja existe o pos dia
    if (strlen($saida_curl) > 0) { 
        #Explode os valores do TXT que sempre serao padroes
        $linha = explode("\"", $saida_curl);
        #Pegue o valor[3] que e o arquivo
        $arquivo = $linha[3];
        #Link para o download do arquivo
        $down = $url . $arquivo;
        #Download
        shell_exec("wget {$down}");
        #Se o arquivo existe agora na maquina, renomeia ele e substitui o atual
        if (file_exists($arquivo)) {
            rename($arquivo, "{$caminho}covid.jpeg");
            #Arrumando a orientacao da imagem
            $infoFoto = exif_read_data("{$caminho}covid.jpeg");
            if (!empty($infoFoto['Orientation'])) {
                $alvo = imagecreatefromjpeg("{$caminho}covid.jpeg");
                switch ($infoFoto['Orientation']) {
                        case 1:
                                $alvo = imagerotate($alvo, 180, 0);
                                break;
                        case 6:
                                $alvo = imagerotate($alvo, 90, 0);
                                break;
                        case 8:
                                $alvo = imagerotate($alvo, -90, 0);
                                break;
                }
                #Final
                imagejpeg($alvo, "{$caminho}covid.jpeg");
                #Destroi temporario
                imagedestroy($alvo);
            }
        }
    }
}
#Liga a funcao
fazer_download();
?>