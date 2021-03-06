<?php
/**
 * Output file for browsing by collection
 *
 * @package Archon
 * @author Mamta Singh, Paul Sorensen
 */
isset($_ARCHON) or die();

$in_Char = isset($_REQUEST['char']) ? $_REQUEST['char'] : NULL;
$in_Book = isset($_REQUEST['books']) ? true : false;
$in_Browse = isset($_REQUEST['browse']) ? true : false;

$objCollectionsTitlePhrase = Phrase::getPhrase('collections_title', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC);
$strCollectionsTitle = $objCollectionsTitlePhrase ? $objCollectionsTitlePhrase->getPhraseValue(ENCODE_HTML) : 'Browse By Collection Title';

if(!$in_Book)
{
   $_ARCHON->PublicInterface->Title = $strCollectionsTitle;
   $_ARCHON->PublicInterface->addNavigation($_ARCHON->PublicInterface->Title, "?p={$_REQUEST['p']}");
}
else
{
   $objBooksTitlePhrase = Phrase::getPhrase('collections_booktitle', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC);
   $strBooksTitle = $objBooksTitlePhrase ? $objBooksTitlePhrase->getPhraseValue(ENCODE_HTML) : 'Browse By Book Title';

   $_ARCHON->PublicInterface->Title = $strBooksTitle;
   $_ARCHON->PublicInterface->addNavigation($_ARCHON->PublicInterface->Title, "?p={$_REQUEST['p']}&amp;books");
}

if($in_Char)
{
   $vars = collections_listCollectionsForChar($in_Char, $in_Book);
}
elseif($in_Browse)
{
   $in_Page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
   $vars = collections_listAllCollections($in_Page, $in_Book);
}
else
{
  $vars['strPageTitle'] = $strCollectionsTitle;
  $objChooseLetterPhrase = Phrase::getPhrase('collections_chooseletter', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC);
  $vars['strSubTitle'] = $objChooseLetterPhrase ? $objChooseLetterPhrase->getPhraseValue(ENCODE_HTML) : 'Choose a letter above to start browsing.';
}

$arrCollectionCount = $_ARCHON->countCollections(true, false, $_SESSION['Archon_RepositoryID']);
$objViewAllPhrase = Phrase::getPhrase('viewall', PACKAGE_CORE, 0, PHRASETYPE_PUBLIC);
$strViewAll = $objViewAllPhrase ? $objViewAllPhrase->getPhraseValue(ENCODE_HTML) : 'View All';
$vars['aToZList'] = generate_collection_atoz_list($arrCollectionCount, $in_Book, $strViewAll);

require_once("header.inc.php");
echo($_ARCHON->PublicInterface->executeTemplate('collections', 'CollectionsNav', $vars));
require_once("footer.inc.php");

function collections_listAllCollections($Page, $ShowBooks)
{
   global $_ARCHON;

   $RepositoryID = $_SESSION['Archon_RepositoryID'] ? $_SESSION['Archon_RepositoryID'] : 0;

   if(!$ShowBooks)
   {
      $arrCollections = $_ARCHON->searchCollections($_REQUEST['q'], SEARCH_COLLECTIONS, 0, 0, 0, $RepositoryID, 0, 0, NULL, NULL, NULL, CONFIG_CORE_PAGINATION_LIMIT + 1, ($Page-1)*CONFIG_CORE_PAGINATION_LIMIT);
      $bookurl = '';
      $template = 'CollectionList';
      $objectName = 'objCollection';

   }
   else
   {
      $arrCollections = $_ARCHON->searchBooks($_REQUEST['q'], 0, 0, 0, CONFIG_CORE_PAGINATION_LIMIT + 1, ($Page-1)*CONFIG_CORE_PAGINATION_LIMIT);
      $bookurl = '&amp;books';
      $template = 'BookList';
      $objectName = 'objBook';


   }

   $objViewAllPhrase = Phrase::getPhrase('viewall', PACKAGE_CORE, 0, PHRASETYPE_PUBLIC);
   $strViewAll = $objViewAllPhrase ? $objViewAllPhrase->getPhraseValue(ENCODE_HTML) : 'View All';

  $objResultsCountPhrase = Phrase::getPhrase('collections_resultscount', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC);
  $strResultsCount = $objResultsCountPhrase ? $objResultsCountPhrase->getPhraseValue(ENCODE_HTML) : ' ($1 found)';
  $strResultsCountHeader = str_replace('$1', count($arrCollections), $strResultsCount);

  if(!$_ARCHON->PublicInterface->Templates[$_ARCHON->Package->APRCode][$template])
   {
      $_ARCHON->declareError("Could not list Collections: CollectionList template not defined for template set {$_ARCHON->PublicInterface->TemplateSet}.");
   }

   $vars['strPageTitle'] = strip_tags($_ARCHON->PublicInterface->Title);
   $vars['strSubTitleClasses'] = 'listitemhead bold';
   $vars['strBackgroundID'] = ' id="listitemwrapper"';
   $content = '';

   if(!$_ARCHON->Error)
   {
      if(!empty($arrCollections))
      {

         $vars['strSubTitle'] = $strViewAll . $strResultsCountHeader;

         foreach($arrCollections as ${$objectName})
         {
            ob_start();
            eval($_ARCHON->PublicInterface->Templates[$_ARCHON->Package->APRCode][$template]);
            $content .= ob_get_contents();
            ob_end_clean();
         }

      }
   }

   $vars['content'] = $content;
   return $vars;
}


function collections_listCollectionsForChar($Char, $ShowBooks)
{

   global $_ARCHON;

   $objBeginningWithPhrase = Phrase::getPhrase('collections_beginningwith', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC);
   $strBeginningWith = $objBeginningWithPhrase ? $objBeginningWithPhrase->getPhraseValue(ENCODE_HTML) : 'Beginning With "$1"';


   $_ARCHON->PublicInterface->addNavigation(str_replace('$1', encoding_strtoupper($Char), $strBeginningWith), "?p={$_REQUEST['p']}&amp;char=$Char");


   $template = (!$ShowBooks) ? 'CollectionList' : 'BookList';

   if(!$_ARCHON->PublicInterface->Templates[$_ARCHON->Package->APRCode][$template])
   {
      $_ARCHON->declareError("Could not list Collections: CollectionList template not defined for template set {$_ARCHON->PublicInterface->TemplateSet}.");
   }


   $vars['strPageTitle'] = strip_tags($_ARCHON->PublicInterface->Title);
   $vars['strSubTitleClasses'] = 'listitemhead bold';
   $vars['strBackgroundID'] = ' id="listitemwrapper"';
   $content = '';

  if(!$_ARCHON->Error)
   {
      if(!$ShowBooks)
      {
         $arrCollections = $_ARCHON->getCollectionsForChar($Char, true, $_SESSION['Archon_RepositoryID'], array('ID', 'Title', 'SortTitle', 'ClassificationID', 'InclusiveDates', 'CollectionIdentifier', 'RepositoryID'));
         $objectName = 'objCollection';
      }
      else
      {
         $arrCollections = $_ARCHON->getBooksForChar($Char);
         $objectName = 'objBook';
      }

      if(!empty($arrCollections))
      {

        $objResultsCountPhrase = Phrase::getPhrase('collections_resultscount', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC);
        $strResultsCount = $objResultsCountPhrase ? $objResultsCountPhrase->getPhraseValue(ENCODE_HTML) : ' ($1 found)';
        $strResultsCountHeader = str_replace('$1', count($arrCollections), $strResultsCount);

        if(!$ShowBooks)
         {
            $objHoldingsBeginningWithPhrase = Phrase::getPhrase('collections_holdingsbeginningwithlist', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC);
            $strHoldingsBeginningWith = $objHoldingsBeginningWithPhrase ? $objHoldingsBeginningWithPhrase->getPhraseValue(ENCODE_HTML) : 'Holdings Beginning With "$1"';
            $strBeginningWithHeader = str_replace('$1', encoding_strtoupper($Char), $strHoldingsBeginningWith);
         }
         else
         {
            $objBooksBeginningWithPhrase = Phrase::getPhrase('collections_booksbeginningwithlist', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC);
            $strBooksBeginningWith = $objBooksBeginningWithPhrase ? $objBooksBeginningWithPhrase->getPhraseValue(ENCODE_HTML) : 'Books Beginning With "$1"';
            $strBeginningWithHeader = str_replace('$1', encoding_strtoupper($Char), $strBooksBeginningWith);
         }

         $vars['strSubTitle'] = $strBeginningWithHeader . $strResultsCountHeader;

         foreach($arrCollections as ${$objectName})
         {
            ob_start();
            eval($_ARCHON->PublicInterface->Templates[$_ARCHON->Package->APRCode][$template]);
            $content .= ob_get_contents();
            ob_end_clean();
         }
      }
   }

   $vars['content'] = $content;
   return $vars;
}

/**
 * Generates the HTML for the A to Z list of collections.
 *
 * @param $arrCollectionCount
 * @param $ShowBooks
 * @param $strViewAll
 * @return string
 */
function generate_collection_atoz_list($arrCollectionCount, $ShowBooks, $strViewAll) {

  $collection_list = '';
  $selected = (isset($_REQUEST['char'])) ? $_REQUEST['char'] : '';
  for($i = 65; $i < 91; $i++)
  {
    $char = chr($i);
    if ($char == $selected) {
      $collection_list .= '<span class="browse-letter selected-char">'. $char . '</span>';
    } else {
      if(!empty($arrCollectionCount[encoding_strtolower($char)]))
      {
        $href = "?p={$_REQUEST['p']}&amp;char=$char";
        if($ShowBooks)
        {
          $href .= "&amp;books";
        }
        $collection_list .= '<a class="browse-letter" href="'.$href.'">'.$char.'</a>';
      }
      else
      {
        $collection_list .= '<span class="browse-letter">'. $char . '</span>';
      }
    }
  }
  $bookurl = ($ShowBooks) ? '&amp;books' : '';
  if (!empty($collection_list)) {
    $collection_list = '<hr /><div class="center"><h2>Show Collections Beginning With:</h2>' . $collection_list;
    if ($strViewAll) {
      $collection_list .= "<br /><a href='?p={$_REQUEST['p']}&amp;browse{$bookurl}'>{$strViewAll}</a>";
    }
    $collection_list .= '</div><hr />';
  }
  return $collection_list;
}
