<?php
#Fazer o download
function fazer_download() {
    #Dia atual
    $data = date('d.m');
    #URL para acesso
    $url = "https://www.limeira.sp.gov.br/sitenovo/downloads/coronavirus/planilha_de_casos/";
    #Buscar o arquivo com base no DOM da pagina e colocar em TXT
    shell_exec("curl {$url} | grep {$data} > saida.txt");
    #Abrir o txt
    $txt = file("saida.txt");
    #Ler as linhas
    foreach($txt as $linha) {
        #Explode os valores do TXT que sempre serao padroes
        $linha = explode("\"", $linha);
        #Pegue o valor[3] que e o arquivo
        $arquivo = $linha[3];
        #Link para o download do arquivo
        $down = $url . $arquivo;
        #Download
        shell_exec("wget {$down}");
        #Se o arquivo existe agora na maquina, renomeia ele e substitui o atual
        if (file_exists($arquivo)) {
            rename($arquivo, "covid.png");
        }
    }
    #Fecha o txt
    fclose($txt);
}
#Liga a funcao
fazer_download();