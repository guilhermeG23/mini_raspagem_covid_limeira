<?php
#Fazer o download
function fazer_download() {
    #Caminho
    $caminho = "/usr/local/www/apache24/data/teste_covid/";
    $arquivo_covid = "{$caminho}covid.jpeg";
    #Dia atual
    $data = date('Y-m-d');
    #Arquivo existe?
    if (file_exists($arquivo_covid) == True) {
        #Dia do arquivo foto atual utilizada
        $data_arquivo = date("Y-m-d", filemtime("covid.jpeg"));
    } else {
        #Deu tudo errado e pega do dia anterior para funcionar
        $data_arquivo = date('Y-m-d', strtotime('-1 day'));
    }
    #Data diferente confirma para continuar a operacao
    if ($data != $data_arquivo) {
        #URL para acesso
        $url = "https://www.limeira.sp.gov.br/sitenovo/downloads/coronavirus/planilha_de_casos/";
        #Buscar o arquivo com base no DOM da pagina e colocar em TXT
        $saida_curl = shell_exec("curl {$url} | grep {$data}");
        #Confere se existe o dia
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
                #Renomear
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
                    #Imagem final
                    imagejpeg($alvo, "{$caminho}covid.jpeg");
                    #Destroi temporario
                    imagedestroy($alvo);
                }
            }
        }
    }
}
#Liga a funcao
fazer_download();
?>
