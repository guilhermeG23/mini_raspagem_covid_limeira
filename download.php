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
        #Fazendo o download e colocando onde eu quero
        rename($arquivo, "{$caminho}covid.png");
    }
}
#Liga a funcao
fazer_download();
?>
