<?php
/**
 * โปรแกรมค้นหาจากภายนอก
 * @author Mahasak Pijittu,
 * @version 1.0.0
 * @package controller
 * @category External
 */
class ExternalSearchController Extends ECMController  {
	/**
	 * action /query ทำการค้นหา และคืนผลลัพธ์
	 *
	 */
	public function queryAction() {
		$keyword = UTFEncode($_POST['keyword']);
		
		$fp = fopen("d:/AppWork/query.txt","a+");
		fwrite($fp,$keyword."\r\n");
		fclose($fp);
		
		$pageTemplate =  "http://192.168.1.224/ECMDev/viewer/default?docID=1723&pageID=1";
		header("Content-Type: text/xml");
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
		<BenefitResponse>
			<ResponseType>ContractInfo</ResponseType>
			<Responses>
				<keyword>{$keyword}</keyword>
				<Contract>
					<ContractID/>
					<LandID/>
					<IssueDate/>
					<ExpireDate/>
					<PageCount>2</PageCount>
					<Pages>
						<Page>
							<PageNo>1</PageNo>
							<PageLink>{$pageTemplate}</PageLink>
							<PageDesc>Contract</PageDesc>
						</Page>
						<Page>
							<PageNo>1</PageNo>
							<PageLink>{$pageTemplate}</PageLink>
							<PageDesc>Contract</PageDesc>
						</Page>
					</Pages>
				</Contract>
				<Contract>
					<ContractID/>
					<LandID/>
					<IssueDate/>
					<ExpireDate/>
					<PageCount>2</PageCount>
					<Pages>
						<Page>
							<PageNo>1</PageNo>
							<PageLink>{$pageTemplate}</PageLink>
							<PageDesc>Contract</PageDesc>
						</Page>
						<Page>
							<PageNo>1</PageNo>
							<PageLink>{$pageTemplate}</PageLink>
							<PageDesc>Contract</PageDesc>
						</Page>
					</Pages>
				</Contract>
				<Contract>
					<ContractID/>
					<LandID/>
					<IssueDate/>
					<ExpireDate/>
					<PageCount>2</PageCount>
					<Pages>
						<Page>
							<PageNo>1</PageNo>
							<PageLink>{$pageTemplate}</PageLink>
							<PageDesc>Contract</PageDesc>
						</Page>
						<Page>
							<PageNo>1</PageNo>
							<PageLink>{$pageTemplate}</PageLink>
							<PageDesc>Contract</PageDesc>
						</Page>
					</Pages>
				</Contract>
			</Responses>
		</BenefitResponse>
		";
		echo $xml;
	}
}
