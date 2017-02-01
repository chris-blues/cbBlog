<?php

switch ($lang)
     {
      case 'de':
        {
         $all = "alle";
         $backToBlog = "Zurück zum Blog";
         $notify = "E-Mail-Adresse";
         $previewString = "Vorschau";
         $emailusage = "um zu weiteren Kommentaren benachrichtigt zu werden";
         $postcomment_restricitions = "Folgende BBCodes sind verfügbar:\n";
         $postcomment_restricitions .= "&#91;b&#93; : fetter Text\n";
         $postcomment_restricitions .= "&#91;u&#93; : unterstrichener Text\n";
         $postcomment_restricitions .= "&#91;s&#93; : durchgestrichener Text\n";
         $postcomment_restricitions .= "&#91;i&#93; : kursiver Text\n";
         $postcomment_restricitions .= "&#91;url&#93; : Link\n";
         $postcomment_restricitions .= "&#91;code&#93; : Programmcode\n";
         $postcomment_restricitions .= "&#91;tt&#93; : Inline-Programmcode\n";
         $postcomment_restricitions .= "&#91;quote&#93; : Zitat\n";
         $postcomment_restricitions .= "&#91;ot&#93; : Offtopic\n";
         $postcomment_restricitions .= "&#91;done&#93; : &#10004;\n";
         $comment = "Ihr Kommentar <button type=\"button\" class=\"notes\" id=\"switchTagHelp\">Hinweise für Formatierung</button>";
         $taghelp = "<h4>Erlaubte Tags:</h4><br>\n<table width=\"100%\">";
         $taghelp .= "<tr><td width=\"30%\">&#91;b&#93;Fetter Text&#91;/b&#93;</td><td><b>Fetter Text</b></td></tr>\n";
         $taghelp .= "<tr><td>&#91;u&#93;unterstrichener Text&#91;/u&#93;</td><td><u>unterstrichener Text</u></td></tr>\n";
         $taghelp .= "<tr><td>&#91;s&#93;durchgestrichener Text&#91;/s&#93;</td><td><s>durchgestrichener Text</s></td></tr>\n";
         $taghelp .= "<tr><td>&#91;i&#93;Kursiver Text&#91;/i&#93;</td><td><i>Kursiver Text</i></td></tr>\n";
         $taghelp .= "<tr><td>&#91;url&#93;Link&#91;/url&#93;</td><td><a href=\"#\">Link</a></td></tr>\n";
         $taghelp .= "<tr><td>&#91;code&#93;Code(\$foo);&#91;/code&#93;</td><td><pre><code>Code(\$foo);</code></pre></td></tr>\n";
         $taghelp .= "<tr><td>Text &#91;tt&#93;Code(\$foo);&#91;/code&#93; etc</td><td>Text <code>Code(\$foo);</code> etc</td></tr>\n";
         $taghelp .= "<tr><td>&#91;quote&#93;Quote&#91;/quote&#93;</td><td><div class=\"quote\"><blockquote class=\"inline\">Zitat</blockquote></div></td></tr>\n";
         $taghelp .= "<tr><td>&#91;ot&#93;Offtopic&#91;/ot&#93;</td><td><span class=\"offtopic\">Offtopic</span></td></tr>\n";
         $taghelp .= "<tr><td>&#91;done&#93;</td><td><span class=\"checkmark\">&#10004;</span></td></tr></table>\n";
         $back = "Zurück";
         $send = "Abschicken";
         $lang_comment = "Kommentar";
         $lang_comments = "Kommentare";
         break;
        }
      case 'en':
        {
         $all = "all";
         $backToBlog = "Back to the blog";
         $notify = "Email-address";
         $previewString = "Preview";
         $emailusage = "to be notified of new comments";
         $postcomment_restricitions = "These BBCodes are available:\n";
         $postcomment_restricitions .= "&#91;b&#93; : bold text\n";
         $postcomment_restricitions .= "&#91;u&#93; : underlined text\n";
         $postcomment_restricitions .= "&#91;s&#93; : stroke text\n";
         $postcomment_restricitions .= "&#91;i&#93; : italic text\n";
         $postcomment_restricitions .= "&#91;url&#93; : Link\n";
         $postcomment_restricitions .= "&#91;code&#93; : Program-code\n";
         $postcomment_restricitions .= "&#91;tt&#93; : Inline Program-code\n";
         $postcomment_restricitions .= "&#91;quote&#93; : Quote\n";
         $postcomment_restricitions .= "&#91;ot&#93; : Offtopic\n";
         $postcomment_restricitions .= "&#91;done&#93; : &#10004;\n";
         $comment = "Your comment <button type=\"button\" class=\"notes\" id=\"switchTagHelp\">Notes for formatting</button>";
         $taghelp = "<h4>Allowed Tags:</h4><br>\n<table width=\"100%\">";
         $taghelp .= "<tr><td width=\"30%\">&#91;b&#93;bold text&#91;/b&#93;</td><td><b>bold text</b></td></tr>\n";
         $taghelp .= "<tr><td>&#91;u&#93;underlined text&#91;/u&#93;</td><td><u>underlined text</u></td></tr>\n";
         $taghelp .= "<tr><td>&#91;s&#93;stroke text&#91;/s&#93;</td><td><s>stroke text</s></td></tr>\n";
         $taghelp .= "<tr><td>&#91;i&#93;italic text&#91;/i&#93;</td><td><i>italic text</i></td></tr>\n";
         $taghelp .= "<tr><td>&#91;url&#93;Link&#91;/url&#93;</td><td><a href=\"#\">Link</a></td></tr>\n";
         $taghelp .= "<tr><td>&#91;code&#93;Code(\$foo);&#91;/code&#93;</td><td><pre><code>Code(\$foo);</code></pre></td></tr>\n";
         $taghelp .= "<tr><td>Text &#91;tt&#93;Code(\$foo);&#91;/tt&#93; etc</td><td>Text <code>Code(\$foo);</code> etc</td></tr>\n";
         $taghelp .= "<tr><td>&#91;quote&#93;Quote&#91;/quote&#93;</td><td><div class=\"quote\"><blockquote class=\"inline\">Quote</blockquote></div></td></tr>\n";
         $taghelp .= "<tr><td>&#91;ot&#93;Offtopic&#91;/ot&#93;</td><td><span class=\"offtopic\">Offtopic</span></td></tr>\n";
         $taghelp .= "<tr><td>&#91;done&#93;</td><td><span class=\"checkmark\">&#10004;</span></td></tr></table>\n";
         $back = "Back";
         $send = "Send";
         $lang_comment = "comment";
         $lang_comments = "comments";
         break;
        }
      default:
        {
         $all = "all";
         $backToBlog = "Back to the blog";
         $notify = "Email-address";
         $previewString = "Preview";
         $emailusage = "to be notified of new comments";
         $postcomment_restricitions = "These BBCodes are available:\n";
         $postcomment_restricitions .= "&#91;b&#93; : bold text\n";
         $postcomment_restricitions .= "&#91;u&#93; : underlined text\n";
         $postcomment_restricitions .= "&#91;s&#93; : stroke text\n";
         $postcomment_restricitions .= "&#91;i&#93; : italic text\n";
         $postcomment_restricitions .= "&#91;url&#93; : Link\n";
         $postcomment_restricitions .= "&#91;code&#93; : Program-code\n";
         $postcomment_restricitions .= "&#91;tt&#93; : Inline Program-code\n";
         $postcomment_restricitions .= "&#91;quote&#93; : Quote\n";
         $postcomment_restricitions .= "&#91;ot&#93; : Offtopic\n";
         $postcomment_restricitions .= "&#91;done&#93; : &#10004;\n";
         $comment = "Your comment <button type=\"button\" class=\"notes\" id=\"switchTagHelp\">Notes for formatting</button>";
         $taghelp = "<h4>Allowed Tags:</h4><br>\n<table width=\"100%\">";
         $taghelp .= "<tr><td width=\"30%\">&#91;b&#93;bold text&#91;/b&#93;</td><td><b>bold text</b></td></tr>\n";
         $taghelp .= "<tr><td>&#91;u&#93;underlined text&#91;/u&#93;</td><td><u>underlined text</u></td></tr>\n";
         $taghelp .= "<tr><td>&#91;s&#93;stroke text&#91;/s&#93;</td><td><s>stroke text</s></td></tr>\n";
         $taghelp .= "<tr><td>&#91;i&#93;italic text&#91;/i&#93;</td><td><i>italic text</i></td></tr>\n";
         $taghelp .= "<tr><td>&#91;url&#93;Link&#91;/url&#93;</td><td><a href=\"#\">Link</a></td></tr>\n";
         $taghelp .= "<tr><td>&#91;code&#93;Code(\$foo);&#91;/code&#93;</td><td><pre><code>Code(\$foo);</code></pre></td></tr>\n";
         $taghelp .= "<tr><td>Text &#91;tt&#93;Code(\$foo);&#91;/tt&#93; etc</td><td>Text <code>Code(\$foo);</code> etc</td></tr>\n";
         $taghelp .= "<tr><td>&#91;quote&#93;Quote&#91;/quote&#93;</td><td><div class=\"quote\"><blockquote class=\"inline\">Quote</blockquote></div></td></tr>\n";
         $taghelp .= "<tr><td>&#91;ot&#93;Offtopic&#91;/ot&#93;</td><td><span class=\"offtopic\">Offtopic</span></td></tr>\n";
         $taghelp .= "<tr><td>&#91;done&#93;</td><td><span class=\"checkmark\">&#10004;</span></td></tr></table>\n";
         $back = "Back";
         $send = "Send";
         $lang_comment = "comment";
         $lang_comments = "comments";
         break;
        }
     }

?>