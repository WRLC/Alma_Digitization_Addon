-- About alma-digitization.lua
--
-- Author: Collin Molony, molony@cua.edu
-- alma-digitization.lua is used for submitting digitization requests to Alma using the Users and Fulfillment API
--
-- set autoSearch to true for this script to automatically run the search when the request is opened.

local settings = {};
settings.autoSearch = GetSetting("AutoSearch");
settings.SubmissionURL = GetSetting("SubmissionURL");
settings.InstitutionCode = GetSetting("InstitutionCode");
settings.UserId = GetSetting("UserId");
settings.regionalURL = GetSetting("RegionalURL");
local interfaceMngr = nil;
local DigitizationForm = {};
DigitizationForm.Form = nil;
DigitizationForm.Browser = nil;
DigitizationForm.RibbonPage = nil;

function Init()
    
	if GetFieldValue("Transaction", "RequestType") == "Article" then
		
		interfaceMngr = GetInterfaceManager();
		
		DigitizationForm.Form = interfaceMngr:CreateForm("WRLC Digitization", "Script");
		DigitizationForm.Browser = DigitizationForm.Form:CreateBrowser("WRLC Digitization", "WRLC Digitization", "WRLC Digitization");
		
		DigitizationForm.Browser.TextVisible = false;
		
		DigitizationForm.Browser.WebBrowser.ScriptErrorsSuppressed = true;
		
		DigitizationForm.RibbonPage = DigitizationForm.Form:GetRibbonPage("WRLC Digitization");
		
		DigitizationForm.RibbonPage:CreateButton("Load Request Page", GetClientImage("Search32"), "LoadRequestPage", "WRLC Digitization");
		
		DigitizationForm.Form:Show();
		
		if settings.autoSearch then
			LoadRequestPage();
		end
	end
end

function NewURL()
	DigitizationForm.Browser:Navigate(settings.FriendsURL);
	
end

function LoadRequestPage()
	instCode =  "instCode=" .. settings.InstitutionCode;
	usrId = "&usrId=" .. settings.UserId;
	illiadCS = "&illiadCS=" .. settings.IlliadClientSecret;
	regionalURL = "&regionalURL=" .. settings.regionalURL;
	itemId = ("&itemId=" .. GetFieldValue("Transaction", "ReferenceNumber")) or "&itemId="
	mmsId = ("&mmsId=" .. GetFieldValue("Transaction", "CallNumber")) or "&mmsId="
	aTitle = ("&aTitle=" .. GetFieldValue("Transaction", "PhotoArticleTitle")) or "&aTitle='unknown'"
	aAuthor = ("&aAuthor=" .. GetFieldValue("Transaction", "PhotoArticleAuthor")) or "&aAuthor='unknown'"
	pageRange = ("&pageRange=" .. GetFieldValue("Transaction", "PhotoJournalInclusivePages")) or "&pageRange='0-0'"
	
	url = "" .. settings.SubmissionURL .. instCode .. usrId .. illiadCS .. itemId .. mmsId .. aTitle .. aAuthor .. pageRange .. regionalURL;
	
	DigitizationForm.Browser:Navigate(url);
	
end
