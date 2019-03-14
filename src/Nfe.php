<?php
namespace Pds\Skeleton;

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class Nfe
{
	public function execute($root = null)
	{
		$this->$root();
	}

	function getCabecalho( Crawler $crawler){

		$cabecalho = [
			'dataEmissao' => '',
			'dataEntradaSaida' => '',
			'modelo' => '',
			'numero' => '',
			'serie' => '',
			'total' => '',
		];

		return $cabecalho;

	}

	function getEmitente( Crawler $crawler){
		$emitente = [
			'nome' => $crawler->filter('td.NFCCabecalho_SubTitulo')->text(),
			'razaoSocial' => '',
			'cnpj' => trim($crawler->filter('td.NFCCabecalho_SubTitulo1')->text()),
			'rua' => '',
			'bairro' => '',
			'cep' => '',
			'cidade' => '',
			'telefone' => '',
			'estado' => '',
			'ibge' => '',
		];

		return $emitente;

	}

	function getProdutos( Crawler $crawler){
		$produtosRaw = $crawler->filter('tr[id^="Item + "]');
		foreach($produtosRaw as $produto){
			$itens = $produto->getElementsByTagName('td');
			$produto = [
				'descricao' => $itens->item(1)->textContent,
				'quantidade' => $itens->item(2)->textContent,
				'unidade' => $itens->item(3)->textContent,
				'valorUnitario' => $itens->item(4)->textContent,
				'valorTotal' => $itens->item(4)->textContent,
				'codigo' => $itens->item(0)->textContent,

			];
			$produtos[] = $produto;
		}

		return $produtos;

	}

    function ce(){
        header('Content-Type: text/html; charset=ISO-8859-15');
        $numero_dae = 201606564771;//, 201604873698 $_GET["codigo_selo"];
        $xx = 'http://www2.sefaz.ce.gov.br/sitram-internet/masterDetailNotaFiscal.do?method=searchRelatorioLista&codigo='.$numero_dae.'&from=NotaFiscalForm';
        $cURL = curl_init();
        curl_setopt($cURL,CURLOPT_URL,$xx);
        curl_setopt($cURL,CURLOPT_HEADER,0);
        curl_setopt($cURL,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
        curl_setopt($cURL,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($cURL,CURLOPT_COOKIESESSION,true);
        curl_setopt($cURL,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($cURL,CURLOPT_ENCODING,'UTF-8');
        $strPage = curl_exec($cURL);
        curl_close($cURL);

        libxml_use_internal_errors(true);
        $page = new \DOMDocument();
        $page->preserveWhiteSpace = false;
        $page->loadHTML($strPage);
        $xpath = new \DomXPath($page);

        $node = $xpath->evaluate('/html/body/div[1]/div[2]/div/section/form[2]/div[3]/div[2]/div[1]/div[2]/div[2]');

        echo "Chave de acesso: {$node[0]->textContent}" . PHP_EOL;

        $expression = '/html/body/div[1]/div[2]/div/section/form[2]/div[3]/div[2]/div[1]/div[11]/div[2]';
        $node = $xpath->evaluate($expression);
        $valorTotal = trim($node[0]->textContent);
        echo "Valor Total: {$valorTotal}" . PHP_EOL;

        $expression = '/html/body/div[1]/div[2]/div/section/form[2]/span/div/div[2]/table/tbody';
        $node = $xpath->evaluate($expression);

        $produtos = $node[0]->childNodes;
        echo count($produtos);
//        foreach ($produtos as $produto){
//            echo $produto->textContent . PHP_EOL;
//        }

        $client = new Client(['cookies' => true]);
        $chaveDeAcesso = '43181012384687000438651040002069031002069033';
        $chaveDeAcesso = '43181012384687000438651020003078229003078222';
        $chaveDeAcesso = '43181093209765016200650040001417721010722180';
        $chaveDeAcesso = '43180907718633001584650050002139521005139526';
        $chaveDeAcesso = '43180905316123000150650020000091901000184323';

        $url = "http://www2.sefaz.ce.gov.br/sitram-internet/dwr/call/plaincall/NotaFiscalAjaxFacade.obterItens.dwr";
        $response = $client->post($url, [
            'callCount' => '1',
            'windowName' => '',
            'c0-scriptName' => 'NotaFiscalAjaxFacade',
            'c0-methodName' => 'obterItens',
            'c0-id' => '0',
            'c0-param0' => 'string:201606564771',
            'c0-param1' => 'number:0',
            'c0-param2' => 'string:',
            'batchId' => '1',
            'page'=> '%2Fsitram-internet%2FmasterDetailNotaFiscal.do%3Fmethod%3DsearchRelatorioLista%26codigo%3D201606564771%26from%3DNotaFiscalForm',
            'httpSessionId' =>  'HyVsYefZ7NqI1ammOoDa454g.dpeap038-inst01',
            'scriptSessionId' => '061FE497F3B64DCADCCC37B57CA7309E',
            ]
        );

echo (string)$response->getBody();

//        $crawler = new Crawler((string)$response->getBody());

//        $iframe = $crawler->filter('#iframeConteudo')->attr('src');
//        $response = $client->get($iframe);
//        $crawler = new Crawler((string) $response->getBody());
//
//        $nfe = [
//            'cabecalho' => $this->getCabecalho($crawler),
//            'emitente' => $this->getEmitente($crawler),
//            'produtos' => $this->getProdutos($crawler),
//        ];
//
//        var_dump($nfe);




    }


	function rs(){
        $client = new Client(['cookies' => true]);
        $chaveDeAcesso = '43181012384687000438651040002069031002069033';
        $chaveDeAcesso = '43181012384687000438651020003078229003078222';
        $chaveDeAcesso = '43181093209765016200650040001417721010722180';
        $chaveDeAcesso = '43180907718633001584650050002139521005139526';
        $chaveDeAcesso = '43180905316123000150650020000091901000184323';

        $url = "https://www.sefaz.rs.gov.br/NFCE/NFCE-COM.aspx?chNFe={$chaveDeAcesso}&nVersao=100&tpAmb=1&cDest=03013156040&dhEmi=323031382D31302D31335431303A34383A32352D30333A3030&vNF=41.33&vICMS=0.00&digVal=4B6C68775A4A4A744D6D396D636264625238794937712B384939303D&cIdToken=000004&cHashQRCode=45EF73C4A9FD11FE6636C6D41678B12F863D6AA7";
        $response = $client->get($url);

        $crawler = new Crawler((string)$response->getBody());

        $iframe = $crawler->filter('#iframeConteudo')->attr('src');
        $response = $client->get($iframe);
        $crawler = new Crawler((string) $response->getBody());

        $nfe = [
            'cabecalho' => $this->getCabecalho($crawler),
            'emitente' => $this->getEmitente($crawler),
            'produtos' => $this->getProdutos($crawler),
        ];

        var_dump($nfe);
    }
}
