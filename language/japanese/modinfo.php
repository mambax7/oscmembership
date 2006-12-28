<?php
/* Japanese provided by Paul Donald (UK), 2006 */
// Module 
//
define("_oscmem_MOD_NAME","OSC会員のモジュール");
define("_oscmem_MOD_DESC","Xoops OSC会員のためのモジュール");
define("_oscmem_ADMENU0","OSC会員の行政");
define("_oscmem_personselect","人の選択");
//Field names
//define("_oscdir_userfile","教会画像");

define("_oscmem_name","会員の名前");
define("_oscmem_lastname", "名字");
define("_oscmem_firstname", "下名前");
define("_oscmem_phone","電話番号");
define("_oscmem_address","住所");
define("_oscmem_city","都・都市");
define("_oscmem_state","州");
define("_oscmem_post","郵便番号");
define("_oscmem_email","メアド");
define("_oscmem_country","国");
define("_oscmem_title","会員帳");
define("_oscmem_birthday","誕生日");
define("_oscmem_month","月");
define("_oscmem_year","年");
define("_oscmem_birthfrom","から");
define("_oscmem_birthto","まで");
define("_oscmem_gender","性別");
define("_oscmem_membershipdate","会員日付");
define("_oscmem_memberclass","分類");
define("_oscmem_submit","提出");
define("_osc_save","保存");
define("_osc_select","選択");
define("_osc_create","作成");
define("_oscmem_actions","行動");
define("_osc_addmember","家族員追加");
define("_oscmem_addmember","会員追加");
define("_oscmem_addingmember","会員追加へリダイレクト");

define("_oscmem_homephone","家の電話");
define("_oscmem_workphone","会社の電話");
define("_oscmem_cellphone","携帯電話");

define("_oscmem_groupname", "族名前");
define("_oscmem_groupdescription","族の記述");
define("_oscmem_grouptype","族の類");

define("_oscmem_workemail","会社でのメアド");

define("_oscmem_male","男性");
define("_oscmem_female","女性");

define("_oscmem_datelastedited","最後編集日付");
define("_oscmem_editedby","編集人");
define("_oscmem_dateentered","入力日付");
define("_oscmem_enteredby","入力人");
define("_oscmem_birthdayinstructions","&nbsp;&nbsp;誕生日フォーマット(月月/日々/年年年年)");
define("_oscmem_weddingdate","結婚日付");

define("_oscmem_familyname","家族の名前");

define("_oscmem_persondetail_TITLE","人の詳しい情報フォーム");
define("_oscmem_familydetail_TITLE","家族の詳しい情報フォーム");
define("_oscmem_person_list","人スト");
define("_oscmem_family_list","家族リスト");
define("_oscmem_familymember","家族員");
define("_oscmem_groupselect_TITLE","族リスト");
define("_oscmem_groupdetail_TITLE","族フォーム");
//Messages
define('_oscmem_UPDATESUCCESS','OSC会員データベースは成功にアップデートされた');
define('_oscmem_CREATESUCCESS_individual','OSCデータベースには人は作成された。');

define("_oscmem_CREATESUCCESS_family","OSCデータベースには家族は作成された。");
define("_oscmem_addfamily_redirect","家族追加へリダイレクト");

define("_oscmem_UPDATESUCCESS_member","家族員追加された。");
define("_oscmem_REMOVEMEMBERSUCCESS","家族員削除された。");
define("_oscmem_nomembers","会員なし");
define("_oscmem_nogroups","族なし。  族を作るために<a href=>ここにクリックして下さい</a>クリックして下さい");

define("_oscmem_persondetail","会員（メンバー）");

//Groups
define("_oscmem_groupmember","族の会員");
define("_osc_addgroupmember","族の会員追加");
define("_oscmem_UPDATESUCCESS_member_grooup","会員（人）成功に追加された");
define("_oscmem_REMOVEGROUPMEMBERSUCCESS","会員（人）成功に取れた");

//Menu
define("_oscmembership_viewperson","会員メンバー");
define("_oscmembership_addperson", "会員追加");
define("_oscmembership_addfamily","家族追加");
define("_oscmembership_viewfamily","家族");
define("_oscmem_remove_member","会員削除");
define("_oscmem_edit_member","会員編集");
define("_oscmem_edit","編集");
define("_oscmembership_viewgroup","族表示");
define("_oscmem_addgroup","族追加");
define("_oscmem_addgroup_redirect","族追加へリダイレクト");


define("_oscmem_familyrole","家族の役割");

define("_oscmem_unassigned","割り当てられていない");

define("_oscmem_optionname","選択名前");
define("_oscmem_optionsequence","順序");
define("_oscmem_osclist_famrole_TITLE","家族の役割");

//define("_oscmem_osclist_TITLE_familyroles","家族の役割");
//define("_oscmem_osclist_TITLE_memberclassifications","会員の分類");
//define("_oscmem_osclist_TITLE_grouptypes","族の類");


define("_oscmem_noclass","分類なし");


define("_oscmem_filter","フィルター");
define("_oscmem_applyfilter","フィルター利用");
define("_oscmem_clearfilter","フィルターなし");
define("_oscmem_addtocart","カートに入れて");
define("_oscmem_remove","カートから除いて");
define("_oscmem_emptycart","カートを空にして");
define("_oscmem_emptycarttogroup","カートを族へ空にして");
define("_oscmem_emptycarttofamily","カートを家族へ空にして");
define("_oscmem_generatelabels","レーベルを生成して");
define("_oscmem_addedtocart","会員はカートに追加した");
define("_oscmem_intersectcart","カート交わる");//combination of groups?
define("_oscmem_removefromcart","カートから除いて");//identical to define("_oscmem_remove","カートから除いて");?


define("_oscmem_msg_removedfromcart","選択された人は成功にカートから除いた");
define("_oscmem_msg_intersectedcart","選択された人は成功にカートと交わった");

define("_oscmem_view_cart","カート表示");
define("_oscmem_cartcontents","カートの内容");
define("_oscmem_memberview","会員を表示");
define("_oscmem_yes","はい");
define("_oscmem_no","いいえ");

define("_oscmem_reporttitle","OSC会員レポート");
define("_oscmem_directoryreport","会員帳");
define("_oscmem_reports", "彙報");
define("_oscmem_nav_reports","彙報やデータ");
define("_oscmem_csvimport","CSVインポート");

define("_oscmem_dirreport_selectclass","含まれる分類を選択して:");
define("_oscmem_usectl","Ctrlキー押しながら選択して");
define("_oscmem_dirreport_groupmemb","族の会員:");
define("_oscmem_dirreport_headhouse","どんな役割は家の「主人」？");
define("_oscmem_dirreport_spouserole","どんな役割は配偶者？");
define("_oscmem_dirreport_childrole","どんな役割は子供？");
define("_oscmem_dirreport_infoinclude","含まれる情報:");

define("_oscmem_address_label","住所:");
define("_oscmem_familyhomephone","家での電話番号");
define("_oscmem_familyworkphone","会社での電話番号");
define("_oscmem_familycellphone","会社での携帯電話番号");
define("_oscmem_familyemail","家族のメアド");
define("_oscmem_personalphone","個人の電話番号");
define("_oscmem_personalworkphone","個人の会社での電話番号");
define("_oscmem_personalcell","個人の携帯電話番号");
define("_oscmem_personalemail","個人のメアド");
define("_oscmem_personalworkemail","個人の会社でのメアド");

define("_oscmem_informationinclude","含まれる情報:");
define("_oscmem_diroptions","ディレクトリの設定");
define("_oscmem_altfamilyname","二重リスティング- 交互の名字");
define("_oscmem_althead","リスティングのために名字だけ使う");
define("_oscmem_dirsort","ディレクトリ並べる");
define("_oscmem_orderbyfirstname","ディレクトリは下の名前で並べる");
define("_oscmem_directorytitle","教会のディレクトリ設定");

define("_oscmem_titlepagesettings","ディレクトリ題名ページ設定");

define("_oscmem_usetitlepageyn","題名ページ利用");
define("_oscmem_churchname_label","教会の名前");
define("_oscmem_disclaimer","免責");

define("_oscmem_directory","ディレクトリ");
define("_oscmem_page","ページ");

define("_oscmem_incorrectdt_membershipdate","会員日付の書式が無効");
define("_oscmem_incorrectdt_weddingdate","結婚日付の書式が無効");

define("_oscmem_CREATESUCCESS_group","族は成功に作成した");
define("_oscmem_csvexport","CSVエクスポート");

define("_oscmem_cvsexport_infoinclude","エクスポートに含むフィールド");


define("_oscmem_season_select","季節を選択");
define("_oscmem_season_spring","春");
define("_oscmem_season_summer","夏");
define("_oscmem_season_fall","秋");
define("_oscmem_season_winter","冬");

define("_oscmem_filters","フィルター");
define("_oscmem_recordstoexport","エクスポートされるレコード");
define("_oscmem_classificationstoexport","エクスポートされる分類");

define("_oscmem_fromfilterbelow","下降のフィルターに基づいたら...");
define("_oscmem_fromcart","カートに含んだ人(フィルター無視)");

define("_oscmem_cvsexport_customfields","エクスポートのカスタムフィールド");
define("_oscmem_rolestoexport","エクスポートに含む家族の役割");

define("_oscmem_gender_nofilter","性別でフィルターしないで");

define("_oscmem_filter_from","から");
define("_oscmem_filter_to","まで");

define("_oscmem_otheremail","他のメアド");
define("_oscmem_envelopenumber","封筒番号");
define("_oscmem_csv_birthanniversary","誕生日・記念日");
define("_oscmem_csv_ageyearsmarried","年齢や結婚している年の数");
define("_oscmem_csv_familyrole","家族の役割");
define("_oscmem_csv_familyname","名字");
define("_oscmem_csv_ministry","聖職");//religious ministry?
define("_oscmem_anniversary","記念日");

define("_oscmem_csv_individual","CSV個々レコード");
define("_oscmem_csv_combinefamily","CSV家族合わせる");
define("_oscmem_csv_addtocart","カートに人を追加");
define("_oscmem_csv_outputmethod","出力方法");

define("_oscmem_csv_skipincompleteaddress","不完全メアドを飛ばす");
define("_oscmem_csv_skipnoenvelope","封筒番号なしレコードを飛ばす<br>(個々レコードだけ)");

define("_oscmem_admin_osclist_familyroles","家族の役割編集");
define("_oscmem_admin_osclist_memberclassification","会員分類編集");
define("_oscmem_admin_osclist_grouptype","族類編集");
define("_oscmem_admin_osclist_permissions","許可編集");
define("_oscmem_admin_customfield","カスタムフィールド編集");

define("_oscmem_view","表示");

define("_oscmem_defaultcountry_value",22);
define("_oscmem_defaultcountry_text"," &nbsp;&nbsp;(既定はアメリカ)");

define("_oscmem_accessdenied","不許可");

?>
