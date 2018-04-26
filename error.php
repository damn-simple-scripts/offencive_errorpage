<?php

$GLOBALS['ua']    = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
$agent            = strtolower($GLOBALS['ua']);
$url              = strtolower($_SERVER['REQUEST_URI']);

$_strpos_jorgee = strpos($agent,'jorgee');

if($_strpos_jorgee !== false) {
	error_reporting(E_ALL);
	/*if (filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) {
		echo "BLOCKED";
		$command = '/var/www/html/safe_exec/fail2ban-client -vvv set nginx-botsearch banip "'.$_SERVER['REMOTE_ADDR'].'"';

		syslog(LOG_INFO, "Fail2Bann'ed user_agent='".$GLOBALS['ua']."' url='".$_SERVER['REQUEST_URI']."' victim=".$_SERVER['REMOTE_ADDR']);
		
		$file = popen($command,"r");
		if ($file == false) {
            		syslog(LOG_INFO, "F2B ($cmd): FAIL");
			exit;
        	}else{
			while (!feof($file)) {
				$line = fgets($file);
				syslog(LOG_INFO, "F2B ($command): ".$line);
			}		
			pclose($file);
		}
		
		exit();
	}*/
	usleep(1000000*5);
      	sendBomb();
      	exit();
}

if (
	strpos($agent, 'mirall') === false &&
	(	
		$agent === "" ||
		startswith($url,'/wp-') || startswith($url,'/sql') || startswith($url,'/w00tw00t') || 
		strpos($agent, 'nikto') !== false || strpos($agent, 'sqlmap') !== false || 
		startswith($url,'/wordpress') || startswith($url,'/wp/') ||
		$_strpos_jorgee !== false ||
		isBadURL($url) || isBadAgent($agent)
	)
)
{
      sendBomb();
      exit();
}else{
	if(!isset($error_code)){
		global $error_code;
		$error_code = 200;
	}
	http_response_code($error_code);
	echo http_response_code();
	exit();
}

function sendBomb(){
        //Turn off output buffering
        if (ob_get_level()) ob_end_clean();

        syslog(LOG_INFO, "Zip Bomb begin of delay user_agent='".$GLOBALS['ua']."' url='".$_SERVER['REQUEST_URI']."' victim=".$_SERVER['REMOTE_ADDR']);
	usleep(900000);
        //prepare the client to recieve GZIP data. This will not be suspicious
        //since most web servers use GZIP by default
        http_response_code(200);
        syslog(LOG_INFO, "Zip Bomb first response user_agent='".$GLOBALS['ua']."' url='".$_SERVER['REQUEST_URI']."' victim=".$_SERVER['REMOTE_ADDR']);
	usleep(100000);
	header("Connection: keep-alive");
	usleep(100000);
        header("Content-Encoding: gzip");
	usleep(100000);
	header("Content-MD5: uM7mBtAi+CBWzxr/Ls9sow==");
	usleep(100000);
	header("Cache-Control: public");
	usleep(100000);
        header("Content-Length: ".filesize('10G.gzip'));
	usleep(200000);

        syslog(LOG_INFO, "Zip Bomb delivered user_agent='".$GLOBALS['ua']."' url='".$_SERVER['REQUEST_URI']."' victim=".$_SERVER['REMOTE_ADDR']);
        //send the gzipped file to the client
        readfile('10G.gzip');
}

function startsWith($haystack,$needle){
    return (strcasecmp(substr($haystack,0,strlen($needle)), $needle) === 0);
}

function isBadURL($url){
	$u = strtolower($url);
	if(
		strpos($u, 'robots.txt') !== false ||
		strpos($u, 'favicon.ico') !== false ||
		strpos($u, 'sitemap.xml') !== false
	){
		return false;
	}

	if(
		strpos($u, 'w00tw00t') !== false ||
		strpos($u, 'a2billing') !== false ||
		strpos($u, 'pma') !== false ||
		strpos($u, 'myadmin') !== false ||
		strpos($u, '.cgi') !== false ||
		strpos($u, 'cgi-bin') !== false ||
		strpos($u, 'admin/i18n') !== false ||
		strpos($u, 'recordings') !== false ||
		strpos($u, 'CherryWeb') !== false ||
		strpos($u, 'command.php') !== false ||
		strpos($u, '.action') !== false ||
		strpos($u, 'xmlrpc.php') !== false ||
		strpos($u, 'proxyradar.com') !== false ||
		strpos($u, '.aspx') !== false ||
		strpos($u, 'stssys.htm') !== false ||
		strpos($u, '/administrator/') !== false ||
		strpos($u, '/sql') !== false ||
		strpos($u, '/db/') !== false ||
		strpos($u, '/admin') !== false ||
		strpos($u, '/mysql') !== false ||
		strpos($u, '/shopdb') !== false ||
		strpos($u, '/install/') !== false ||
		strpos($u, '/joomla/') !== false ||
		startsWith($u, "http") ||
		startsWith($u, "/readme") ||
		strpos($u, '\x05\x02') !== false ||
		strpos($u, 'HNAP1/') !== false ||
		strpos($u, 'passwd') !== false ||
		strpos($u, '/wp') !== false ||
		strpos($u, 'wordpress') !== false
	){
		return true;
	}
	return false;
}

function isBadAgent($a){
	if(strlen($a) == 0){
		return true;
	}
	if(strcasecmp($a, "google") === 0){
		return true;
	}else if(strpos($a, "google") !== false){
		return false;
	}

	$check_arr = null;
	switch($a[0]){
		case 'a':
			if (strpos($a, 'archive.org_bot') !== false){
				return false;
			}
			$check_arr = array(
				"abac", "abach", "abby", "aberja", "abilon", "abont", "abot", "aboutoil", "accept",
				"access", "accoo", "accoon", "aceftp", "acme", "active", "address", "adopt", "adress",
				"advisor", "agent", "ahead", "aihit", "aipbot", "Akregat", "aktuelles", "alarm",
				"albert", "alek", "alexa toolbar", "Alexibot", "Alligator", "AllSubmitter", "alltop",
				"alma", "almaden", "alot", "ALot", "alpha", "america online browser", "amfi", "amfibi",
				"amzn_assoc", "anal", "Anarchie", "andit", "anon", "AnotherBot", "ansearch", "answer",
				"answerbus", "answerchase", "antivirx", "Apexoo", "apollo", "appie", "Aqua_Products",
				"arach", "Arachmo", "arian", "asps","ASPSe", "ASSORT", "aster", "atari", "ATHENS","AtHome",
				"atlocal", "Atomic_Email_Hunter", "Atomz", "atrax", "atrop", "^attach", "attrib", "autoemailspider",
				"autoh", "autohot", "autohttp", "av fetch", "avsearch", "axod", "axon"
			);
			break;
		case 'b':
			if (strpos($a, 'baidu') !== false) {
				return false;
			}
			$check_arr = array(
				"b2w", "baboom", "baby", "back", "BackDoorBot", "BackStreet", "BackWeb", "Badass", "bali",
				"bandit", "barry", "BatchFTP", "bdfetch", "beat", "become", "bee", "berts", "betabot",
				"bew", "big.brother", "Bigfoot", "biglotron", "bilgi", "binlar", "bison", "bitacle",
				"Biz360", "Black.Hole", "BlackWidow", "bladder.fusion", "Blaiz", "blitz", "Blog.Checker",
				"blogl", "BlogPeople", "blogscope", "Blogshares.Spiders", "blogzice", "bloob", "Bloodhound",
				"bmclient", "Board", "boitho", "bond", "Bookmark.search.tool", "boris", "Bost", "Boston.Project",
				"BotALot", "bot.ara", "botje", "Bot.mailto:craftbot@yahoo.com", "botpaidtoclick", "BotRightHere",
				"botw", "bpimage", "brand", "BravoBrian", "brok", "Bropwers", "broth", "browseabit", "browsex",
				"bsalsa", "Buddy", "Build", "built", "bulls", "bumble", "bunny", "busca", "busi", "buy", "bwh3"
			);
			break;
		case 'c':
			if (startsWith($a, 'curl')){
				return false;
			}
			$check_arr = array(
				"cafek", "cafi", "camel", "cand", "captu", "casper", "Catch", "ccbot", "ccubee", "cd34", "ceg", "cfnetwork",
				"CFNetwork", "cgichk", "cha0s", "chang", "chaos", "char(", "charlotte", "chase x", "checker", "check_http",
				"checkonly", "checkprivacy", "CheeseBot", "Chek", "CherryPicker", "chill", "ChinaClaw", "chttpclient",
				"CICC", "cipinet", "Cisco", "cita", "citeseer", "Clam", "claria", "Claw", "Click.Bot", "clipping", "clshttp",
				"Clush", "cmsworldmap", "COAST", "code.com", "cogent", "ColdFusion", "collect", "comb", "combine", "commentreader",
				"common", "comodo", "Compan", "compatible-", "conc", "conduc", "contact", "Control", "contype", "conv", 
				"cool", "Copernic", "copi", "copy", "coral", "core-project", "corn", "cosmos", "costa", "cowbot", "cr4nk", "craft",
				"cralwer", "crank", "crap", "crazy", "cres", "cs-cz", "cshttp", "c-spider", "cuill", "CURI", "curious", "curry",
				"custo", "cute", "cyberalert", "cz3", "czx"
			);
			break;
		case 'd':
			if (strpos($a, 'duckduck') !== false ){
				return false;
			}
			$check_arr = array(
				"daily", "dalvik", "daobot", "dark", "darwin", "data", "daten", "Daum", "dcbot", "dcs", "dds explorer",
				"deep", "deps", "DepS", "detect", "Deweb", "dex", "diam", "diavol", "Digger", "Digimarc", "digout4uagent",
				"diibot", "dillo", "ding", "disc", "discobot", "disp", "ditto", "dlc", "DnloadMage", "doco", "dotbot",
				"DotBot", "Doubanbot", "Download", "Download.Demon", "Download.Devil", "Downloader", "Download.Wonder",
				"drag", "DreamPassport", "drec", "Drip", "dsdl", "dsok", "DSurf", "DTAAgent", "dts", "Dual", "dumb", "DynaWeb"
			);
			break;
		case 'e':
			$check_arr = array(
				"eag", "earn", "earthcom", "easydl", "ebin", "EBM-APPLE", "EBrowse", "eCatch", "echo", "ecollector", "e-collector",
				"edco", "edgeio", "efp@gmx.net", "egoto", "EirGrabber", "elnsb5", "email", "EmailCollector", "Email.Extractor",
				"EmailSearch", "EmailSiphon", "EmailWolf", "Emer", "empas", "encyclo", "enfi", "enhan", "enterprise_search",
				"envolk", "erck", "erocr", "ESurf", "Eval", "eventax", "evere", "evil", "ewh", "Exabot", "Exact", "exploit",
				"EXPLOITER", "expre", "extra", "ExtractorPro", "EyeN"
			);
			break;
		case 'f':
			if (startsWith($a, 'feedly')){
				return false;
			}
			$check_arr = array(
				"FairAd", "Fake", "fang", "fast", "fastbug", "fastlwspider", "FavOrg", "Favorites.Sweeper", "faxo", "FDM_1",
				"fdse", "feed24", "feeddisc", "feedhub", "fetch", "FEZhead", "filan", "fileboo", "FileHound", "fimap",
				"find", "firebat", "firedownload/1.2pre firefox/3.6", "firefox/0", "firefox/2", "Firefox.2.0", "firs",
				"flam", "flash", "flexum", "FlickBot", "flip", "fluffy", "flunky", "fly", "focus", "Foob", "fooky",
				"forum", "forv", "fost", "foto", "foun", "fount", "foxy/1;", "Franklin.Locator", "freefind", "FreshDownload",
				"frontpage", "FSurf", "fuck", "futile", "fyber"
			);
			break;
		case 'g':
			$check_arr = array(
				"gais", "GalaxyBot", "galbot", "Gamespy_Arcade", "gbpl", "GbPl", "gecko/2001", "gecko/2002", "gecko/2006",
				"gecko/2009042316", "gener", "geni", "geo", "geona", "Get", "geth", "getr", "getw", "ggl", "gigabaz",
				"Ginxbot", "gira", "gluc", "glx.?v", "gnome", "goforit", "goldfire", "gonzo", "GornKer", "gosearch",
				"got-it", "gozilla", "go!zilla", "Go.Zilla", "grab", "Grabber", "GrabNet", "graf", "Green.Research",
				"greg", "grub", "grub-client", "grup", "gsa-cra", "gt::www", "guidebot", "guruji", "gvfs", "gyps"
			);
			break;
		case 'h':
			$check_arr = array(
				"hack", "haha", "hailo", "harv", "hash", "hatena", "hax", "Hax", "head", "helm",
				"herit", "heritrix", "hgre", "hhjhj@yahoo", "hippo", "hloader", "hmse", "HMSE",
				"hmview", "HMView", "holm", "holy", "HomePageSearch", "HooWWWer", "hotbar 4.4.5.0",
				"HouxouCrawler", "hpprint", "HPPrint", "htdig", "httpclient", "httpconnect", "httpdown",
				"http.generic", "HTTPGet", "httplib", "HTTPRetriever", "HTTrack", "human", "huron",
				"hverify", "hybrid", "hyper"
			);
			break;
		case 'i':
			$check_arr = array(
				"iaskspi", "ibm evv", "IBM_Planetwide", "iccra", "ichiro", "icopy", "IDA", "IDBot", "ID-Search", "ie/5.0",
				"ieauto", "iempt", "iexplore.exe", "iGetter", "iimds_monitor", "ilium", "ilse", "iltrov", "Iltrov", "imagefetch",
				"Image.Stripper", "Image.Sucker", "Incutio", "IncyWincy", "indexer", "Industry.Program", "indy", "ineturl",
				"infonav", "informant", "InfoTekies", "Ingelin", "innerpr", "inspect", "InstallShield.DigitalWizard",
				"insuran", "intellig", "Intelliseek", "InterGET", "Internet_Explorer", "InternetLinkagent", "Internet.Ninja",
				"InternetSeer.com", "internetx", "Internet.x", "intraf", "ip2", "ipbot", "ipsel", "Iria", "irlbot", "Iron33",
				"Irvine", "isc_sys", "isilo", "isrccrawler", "isspi", "IUPUI.Research.Bot"
			);
			break;
		case 'j':
			$check_arr = array(
				"jady", "jaka", "jam", "Jam", "java/", "Java(tm)", "JBH.agent", "jenn", "Jenny", "JetB", "JetC",
				"jeteye", "jiro", "jobo", "joc", "jupit", "just", "jyx", "jyxo"
			);
			break;
		case 'k':
			$check_arr = array(
				"Kapere", "kash", "kazo", "kbee", "kenjin", "kernel", "keywo", "kfsw", "kkma", "kmc", "know", "kosmix",
				"krae", "KRetrieve", "krug", "ksibot", "ksoap", "ktxn", "kum", "KWebGet"
			);
			break;
		case 'l':
			if (startsWith($a, 'libcurl')){
				return false;
			}
			$check_arr = array(
				"labs", "Lachesis", "lanshan", "lapo", "larbin", "leacher", "leech", "LeechFTP", "LeechGet", "leipzig.de",
				"lets", "lexi", "lexxe", "lftp", "libby", "libcrawl", "libfetch", "libghttp", "libweb", "libwhisker",
				"libwww", "libwww-FM", "libwww-perl", "light", "LightningDownload", "likse", "linc", "lingue", "Link",
				"linkcheck", "LinkextractorPro", "Linkie", "linklint", "linkman", "LINKS.ARoMATIZED", "LinkScan",
				"Link.Sleuth", "linktiger", "LinkWalker", "lint", "list", "litefeeds", "livedoor", "livejournal", "liveup",
				"lmcrawler", "lmq", "LNSpiderguy", "loader", "LocalcomBot", "locu", "london", "lone", "looksmart", "loop",
				"lork", "lth_", "lwp", "lwp-request", "lwp-request", "lwp-trivial"
			);
			break;
		case 'm':
			if (
				strpos($a, 'aolbuild') !== false || strpos($a, 'baidu') !== false || strpos($a, 'bingbot') !== false ||
				strpos($a, 'bingpreview') !== false || strpos($a, 'msnbot') !== false || strpos($a, 'teoma') !== false ||
				strpos($a, 'slurp') !== false || strpos($a, 'yandex') !== false || strpos($a, 'mirall') !== false
			) {
				return false;
			}

			if (
				strpos($a, 'jorgee') !== false
			){
				return true;
			}
			$check_arr = array(
				"mac_f", "Mac.Finder", "Macintosh;.I;.PPC", "magi", "Magnet", "Mag-Net", "magp", "mail.ru", "Mail.Sweeper",
				"main", "majest", "mam", "mana", "MarcoPolo", "mark.blonin", "marketwire", "MarkWatch", "MaSagool", "masc",
				"mass", "Mass.Downloader", "mata", "mavi", "mcbot", "McBot", "MCspider", "mecha", "mechanize", "MEGAUPLOAD",
				"metadata", "metalogger", "MetaProducts.Download.Express", "metaspin", "metauri", "mete", "mib/2.2", "Microsoft.Data.Access",
				"microsoft_internet_explorer", "mido", "miggi", "miix", "mindjet", "mindman", "miner", "mips", "mira",
				"mire", "Mirror", "miss", "Missauga", "Missigua.Locator", "Missouri.College.Browse", "mist", "mizz", "mj12",
				"mkdb", "mlbot", "mlm", "MMMoCrawl", "mnog", "MnoG", "moge", "moje", "Monster", "Monza.Browser", "mooz",
				"more", "Moreoverbot", "mothra/netscan", "MOT-MPx220", "mouse", "MovableType", "mozdex", "Mp3Bot", "MPF",
				"MRA", "MSFrontPage", "MS.FrontPage", "MSIE6", "MSIE_6.0", "MSIECrawler", "MSNPTC", "MSProxy", "MSRBOT",
				"multithreaddb", "musc", "MVAC", "mvi", "MWM", "My_age", "MyApp", "MyDog", "MyEng", "MyFamilyBot", "MyGetRight",
				"MyIE2", "mysearch", "myurl"
			);
			break;
		case 'n':
			$check_arr = array(
				"NAG", "NAMEPROTECT", "NASA.Search", "nationaldirectory", "Naver", "Navr", "Near", "NetAnts", "netattache",
				"Netcach", "NetCarta", "Netcraft", "NetCrawl", "NetMech", "netprospector", "NetResearchServer", "NetSp",
				"Net.Vampire", "netX", "NetZ", "Neut", "newLISP", "NewsGatorInbox", "NEWT", "NEWT.ActiveX", "Next",
				"NG", "NICE", "nikto", "Nimb", "Ninja", "Ninte", "NIPGCrawler", "Noga", "nogo", "Noko", "Nomad", "Norb",
				"noxtrumbot", "NPbot", "NuSe", "Nutch", "Nutex", "NWSp"
			);
			break;
		case 'o':
			$check_arr = array(
				"Obje", "Ocel", "Octo", "ODI3", "oegp", "Offline", "Offline.Explorer", "Offline.Navigator", "OK.Mozilla",
				"omg", "Omni", "Onfo", "onyx", "OpaL", "OpenBot", "Openf", "OpenTextSiteCrawler", "OpenU", "Orac",
				"OrangeBot", "Orbit", "Oreg", "osis", "Outf", "Owl"
			);
			break;
		case 'p':
			$check_arr = array(
				"P3P", "PackRat", "PageGrabber", "PagmIEDownload", "pansci", "Papa", "Pars", "Patw", "pavu", "Pb2Pb", 
				"pcBrow", "PEAR", "PECL", "PEER", "pepe", "Perl", "PerMan", "PersonaPilot", "Persuader", "petit", "PHP",
				"PHPot", "PHP.vers", "Phras", "PicaLo", "Piff", "Pige", "pigs", "Ping", "PingALink", "Pingd", "Pipe",
				"Plag", "planetwork", "Plant", "playstarmusic", "Pluck", "Pockey", "POE-Com", "Poirot", "Pomp", "Port.Huron",
				"Post", "powerset", "Preload", "press", "Privoxy", "Probe", "Program.Shareware", "Progressive.Download",
				"ProPowerBot", "prospector", "Provider.Protocol.Discover", "ProWebWalker", "Prowl", "Proxy", "Prozilla",
				"psbot", "PSurf", "psycheclone", "puf", "Pulse", "Pump", "purebot", "PushSite", "PussyCat", "PuxaRapido",
				"pycurl", "PycURL", "PyQ"
			);
			break;
		case 'q':
			$check_arr = array(
				"QRVA", "QuepasaCreep", "Query", "Quest", "Qweer"
			);
			break;
		case 'r':
			$check_arr = array(
				"radian", "Radiation", "Rambler", "RAMP", "RealDownload", "Reap", "Recorder", "RedCarpet", "RedKernel",
				"ReGet", "relevantnoise", "replacer", "Repo", "requ", "Rese", "Retrieve", "Rip", "Rix", "RMA", "Roboz",
				"Rogue", "Rover", "RPT-HTTP", "Rsync", "RTG30", "ruby", "Rufus"
			);
			break;
		case 's':
			$check_arr = array(
				"Salt", "Sample", "SAPO", "Sauger", "savvy", "SBIder", "SBP", "SCAgent", "scan", "SCEJ_", "Scanbot", "Sched",
				"Schizo", "Schlong", "Schmo", "Scooter", "Scorp", "Scout", "ScoutOut", "SCrawl", "screen", "script",
				"SearchExpress", "searchhippo", "Searchme", "searchpreview", "searchterms", "Second.Street.Research",
				"Security.Kol", "Seekbot", "Seeker", "Sega", "Sensis", "Sept", "Serious", "Sezn", "Shai", "Share",
				"Sharp", "Shaz", "shell", "shelo", "Sherl", "Shim", "Shiretoko", "ShopWiki", "SickleBot", "Simple",
				"Siph", "sitecheck", "SiteCrawler", "SiteSnagger", "Site.Sniper", "SiteSucker", "sitevigil", "SiteX",
				"skygrid", "Sleip", "Slide", "Slurpy.Verifier", "Sly", "Smag", "SmartDownload", "Smurf", "sna-", "snag",
				"Snake", "Snapbot", "Snip", "Snoop", "SocSci", "sogou", "Sohu", "solr", "So-net", "sootle", "Soso",
				"SpaceBison", "Spad", "Span", "spanner", "Speed", "Spegla", "Sphere", "Sphider", "spider", "SpiderBot",
				"SpiderEngine", "SpiderView", "Spin", "sproose", "Spurl", "Spyder", "Squi", "SQ.Webscanner", "sqwid",
				"Sqworm", "SSM_Ag", "Stack", "Stamina", "stamp", "Stanford", "Statbot", "State", "Steel", "Strateg",
				"Stress", "Strip", "studybot", "Style", "subot", "Suck", "sucker", "Sume", "sun4m", "Sunrise", "SuperBot",
				"SuperBro", "SuperHTTP", "Supervi", "Surf4Me", "Surfbot", "SurfWalker", "Susi", "suza", "suzu", "Sweep",
				"sygol", "syncrisis", "Systems", "Szukacz"
			);
			break;
		case 't':
			$check_arr = array(
				"Tagger", "Tagyu", "tAke", "Talkro", "TALWinHttpClient", "tamu", "Tandem", "Tarantula", "tarspider",
				"tBot", "TCF", "Tcs/1", "TeamSoft", "Tecomi", "Teleport", "Telesoft", "Templeton", "Tencent", "Terrawiz",
				"Test", "TexNut", "The.Intraformant", "TheNomad", "Thomas", "TightTwatBot", "Timely", "Titan", "TMCrawler",
				"TMhtload", "toCrawl", "Todobr", "Tongco", "topic", "Torrent", "Track", "translate", "Traveler", "TREEVIEW",
				"trivial", "True", "Tunnel", "turing", "turnit", "Turnitin", "TutorGig", "TV33_Mercator", "Twat", "Tweak",
				"Twice", "Twisted.PageGetter", "Tygo"
			);
			break;
		case 'u':
			$check_arr = array(
				"ubee", "UCmore", "UdmSearch", "UIowaCrawler", "Ultraseek", "UMBC", "unf", "UniversalFeedParser",
				"unknown", "UPG1", "URLBase", "URL.Control", "urldispatcher", "URLGetFile", "urllib", "URLSpiderPro",
				"URL_Spider_Pro", "URLy", "UserAgent", "User-Agent", "USyd", "UtilMind"
			);
			break;
		case 'v':
			$check_arr = array(
				"Vacuum", "vagabo", "Valet", "Valid", "Vamp", "vayala", "VB_", "VCI", "verif", "VERI~LI", "versus",
				"via", "Viewer", "vikspid", "virtual", "visibilitygap", "Visual", "vobsub", "Void", "VoilaBot",
				"voyager", "vspider", "VSyn"
			);
			break;
		case 'w':
			if(strpos($a, "wget") !== false){
				return false;
			}
			$check_arr = array(
				"w0000t", "w3search", "walhello", "Walker", "Wand", "WAOL", "WAPT", "Watch", "Wavefire", "wbdbot",
				"Weather", "Web2Mal", "Web2WAP", "WebaltBot", "WebAuto", "WebBandit", "Webbot", "web.by.mail",
				"WebCapture", "WebCat", "Webclip", "webcollage", "WebCollector", "WebCopier", "WebCopy", "WebCor",
				"webcraft@bea", "webcrawl", "WebDat", "Web.Data.Extractor", "webdevil", "webdownloader",
				"Web.Downloader", "Webdup", "WebEMail", "WebEMailExtrac", "WebEnhancer", "WebFetch", "WebFilter",
				"WebFountain", "WebGo", "WebHook", "Web.Ima", "Webinator", "WebInd", "webitpr", "WebLea", "Webmaster",
				"WebmasterWorldForumBot", "WebMin", "WebMirror", "webmole", "Web.Mole", "webpic", "WebPin", "WebPix",
				"WebReaper", "WebRipper", "WebRobot", "WebSauger", "WebSite", "Website.eXtractor", "Website.Quester",
				"WebSnake", "webspider", "Webster", "WebStripper", "websucker", "Web.Sucker", "WebTre", "WebVac",
				"webwalk", "WebWasher", "WebWeasel", "WebWhacker", "WebZIP", "Wells", "WEP_S", "WEP.Search.00",
				"WeRelateBot", "Whack", "Whacker", "whiz", "WhosTalking", "Widow", "Wildsoft.Surfer", "Win67", "window.location",
				"Windows.95", "Windows.95", "Windows.98", "Windows.98", "WinHT", "winhttp", "WinHttpRequest", "WinHTTrack",
				"Winnie.Poh", "Winodws", "wire", "WISEbot", "wisenutbot", "wish", "Wizz", "WordP", "Works", "world",
				"w:PACBHO60", "WUMPUS", "Wweb", "WWW-Collector", "WWW.Mechanize", "WWWOFFLE", "www.ranks.nl", "wwwster"
			);
			break;
		case 'x':
			if( strcasecmp($a, "x") === 0){
				return true;
			}
			$check_arr = array(
				"X12R1", "Xaldon", "Xenu", "XGET", "xirq", "x-Tractor"
			);
			break;
		case 'y':
			if(startsWith($a, "yacy") || startsWith($a, "yahoo")){
				return false;
			}
			$check_arr = array(
				"YaDirectBot", "Yamm", "Yand", "yang", "Yeti", "Y!OASIS", "Yoono", "yori", "Yotta", 
				"YTunnel", "Y!Tunnel"
			);
			break;
		case 'z':
			if(startsWith($a, "zeal")){
				return false;
			}else if(strcasecmp($a, "zmeu")){
				return true;
			}
			$check_arr = array(
				"Zade", "zagre", "ZBot", "ZeBot", "zerx", "Zeus", "ZIPCode", "Zixy", "zmao", "zmeu",
				"zune", "Zyborg"
			);
			break;
		default:
			return true;
	}

	foreach($check_arr AS $check){
		if(strcasecmp($a, $check) === 0){
			return true;
		}else if(startsWith($a, $check)){
			return true;
		}else if(strlen($check) > 8){
			if(strpos($a, strtolower($check)) !== false){
				return true;
			}
		}
	}
	return false;
}
