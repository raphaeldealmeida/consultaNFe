<?php
namespace Pds\Skeleton;

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class Nfe
{
	public function execute($root = null)
	{
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
}