/*! twtexibinjax.js v1.00.00 
| (c) 2015 crowdcc. 
| combine * twitter-text-1.9.1.js * exif.js * binaryajax.js 
| twitter-text-1.9.1.js * twitter text for compliance with twitter tweet rules
| exif.js * photo orientation prepare for posting
| binaryajax.js * posting photo utility 
| crowdcc.com/use */


/*! twitter-text-js 1.9.1  */

(function() {
  if (typeof ccc.str.twttr === "undefined" || ccc.str.twttr === null) {
      ccc.str.twttr = {};
  }

  ccc.str.twttr.txt = {};
  ccc.str.twttr.txt.regexen = {};

  var HTML_ENTITIES = {
    '&': '&amp;',
    '>': '&gt;',
    '<': '&lt;',
    '"': '&quot;',
    "'": '&#39;'
  };

  // HTML escaping
  ccc.str.twttr.txt.htmlEscape = function(text) {
    return text && text.replace(/[&"'><]/g, function(character) {
      return HTML_ENTITIES[character];
    });
  };

  // Builds a RegExp
  function regexSupplant(regex, flags) {
    flags = flags || "";
    if (typeof regex !== "string") {
      if (regex.global && flags.indexOf("g") < 0) {
        flags += "g";
      }
      if (regex.ignoreCase && flags.indexOf("i") < 0) {
        flags += "i";
      }
      if (regex.multiline && flags.indexOf("m") < 0) {
        flags += "m";
      }

      regex = regex.source;
    }

    return new RegExp(regex.replace(/#\{(\w+)\}/g, function(match, name) {
      var newRegex = ccc.str.twttr.txt.regexen[name] || "";
      if (typeof newRegex !== "string") {
        newRegex = newRegex.source;
      }
      return newRegex;
    }), flags);
  }

  ccc.str.twttr.txt.regexSupplant = regexSupplant;

  // simple string interpolation
  function stringSupplant(str, values) {
    return str.replace(/#\{(\w+)\}/g, function(match, name) {
      return values[name] || "";
    });
  }

  ccc.str.twttr.txt.stringSupplant = stringSupplant;

  function addCharsToCharClass(charClass, start, end) {
    var s = String.fromCharCode(start);
    if (end !== start) {
      s += "-" + String.fromCharCode(end);
    }
    charClass.push(s);
    return charClass;
  }

  ccc.str.twttr.txt.addCharsToCharClass = addCharsToCharClass;

  // Space is more than %20, U+3000 for example is the full-width space used with Kanji. Provide a short-hand
  // to access both the list of characters and a pattern suitible for use with String#split
  // Taken from: ActiveSupport::Multibyte::Handlers::UTF8Handler::UNICODE_WHITESPACE
  var fromCode = String.fromCharCode;
  var UNICODE_SPACES = [
    fromCode(0x0020), // White_Space # Zs       SPACE
    fromCode(0x0085), // White_Space # Cc       <control-0085>
    fromCode(0x00A0), // White_Space # Zs       NO-BREAK SPACE
    fromCode(0x1680), // White_Space # Zs       OGHAM SPACE MARK
    fromCode(0x180E), // White_Space # Zs       MONGOLIAN VOWEL SEPARATOR
    fromCode(0x2028), // White_Space # Zl       LINE SEPARATOR
    fromCode(0x2029), // White_Space # Zp       PARAGRAPH SEPARATOR
    fromCode(0x202F), // White_Space # Zs       NARROW NO-BREAK SPACE
    fromCode(0x205F), // White_Space # Zs       MEDIUM MATHEMATICAL SPACE
    fromCode(0x3000)  // White_Space # Zs       IDEOGRAPHIC SPACE
  ];
  addCharsToCharClass(UNICODE_SPACES, 0x009, 0x00D); // White_Space # Cc   [5] <control-0009>..<control-000D>
  addCharsToCharClass(UNICODE_SPACES, 0x2000, 0x200A); // White_Space # Zs  [11] EN QUAD..HAIR SPACE

  var INVALID_CHARS = [
    fromCode(0xFFFE),
    fromCode(0xFEFF), // BOM
    fromCode(0xFFFF) // Special
  ];
  addCharsToCharClass(INVALID_CHARS, 0x202A, 0x202E); // Directional change

  ccc.str.twttr.txt.regexen.spaces_group = regexSupplant(UNICODE_SPACES.join(""));
  ccc.str.twttr.txt.regexen.spaces = regexSupplant("[" + UNICODE_SPACES.join("") + "]");
  ccc.str.twttr.txt.regexen.invalid_chars_group = regexSupplant(INVALID_CHARS.join(""));
  ccc.str.twttr.txt.regexen.punct = /\!'#%&'\(\)*\+,\\\-\.\/:;<=>\?@\[\]\^_{|}~\$/;
  ccc.str.twttr.txt.regexen.rtl_chars = /[\u0600-\u06FF]|[\u0750-\u077F]|[\u0590-\u05FF]|[\uFE70-\uFEFF]/mg;
  ccc.str.twttr.txt.regexen.non_bmp_code_pairs = /[\uD800-\uDBFF][\uDC00-\uDFFF]/mg;

  var nonLatinHashtagChars = [];
  // Cyrillic
  addCharsToCharClass(nonLatinHashtagChars, 0x0400, 0x04ff); // Cyrillic
  addCharsToCharClass(nonLatinHashtagChars, 0x0500, 0x0527); // Cyrillic Supplement
  addCharsToCharClass(nonLatinHashtagChars, 0x2de0, 0x2dff); // Cyrillic Extended A
  addCharsToCharClass(nonLatinHashtagChars, 0xa640, 0xa69f); // Cyrillic Extended B
  // Hebrew
  addCharsToCharClass(nonLatinHashtagChars, 0x0591, 0x05bf); // Hebrew
  addCharsToCharClass(nonLatinHashtagChars, 0x05c1, 0x05c2);
  addCharsToCharClass(nonLatinHashtagChars, 0x05c4, 0x05c5);
  addCharsToCharClass(nonLatinHashtagChars, 0x05c7, 0x05c7);
  addCharsToCharClass(nonLatinHashtagChars, 0x05d0, 0x05ea);
  addCharsToCharClass(nonLatinHashtagChars, 0x05f0, 0x05f4);
  addCharsToCharClass(nonLatinHashtagChars, 0xfb12, 0xfb28); // Hebrew Presentation Forms
  addCharsToCharClass(nonLatinHashtagChars, 0xfb2a, 0xfb36);
  addCharsToCharClass(nonLatinHashtagChars, 0xfb38, 0xfb3c);
  addCharsToCharClass(nonLatinHashtagChars, 0xfb3e, 0xfb3e);
  addCharsToCharClass(nonLatinHashtagChars, 0xfb40, 0xfb41);
  addCharsToCharClass(nonLatinHashtagChars, 0xfb43, 0xfb44);
  addCharsToCharClass(nonLatinHashtagChars, 0xfb46, 0xfb4f);
  // Arabic
  addCharsToCharClass(nonLatinHashtagChars, 0x0610, 0x061a); // Arabic
  addCharsToCharClass(nonLatinHashtagChars, 0x0620, 0x065f);
  addCharsToCharClass(nonLatinHashtagChars, 0x066e, 0x06d3);
  addCharsToCharClass(nonLatinHashtagChars, 0x06d5, 0x06dc);
  addCharsToCharClass(nonLatinHashtagChars, 0x06de, 0x06e8);
  addCharsToCharClass(nonLatinHashtagChars, 0x06ea, 0x06ef);
  addCharsToCharClass(nonLatinHashtagChars, 0x06fa, 0x06fc);
  addCharsToCharClass(nonLatinHashtagChars, 0x06ff, 0x06ff);
  addCharsToCharClass(nonLatinHashtagChars, 0x0750, 0x077f); // Arabic Supplement
  addCharsToCharClass(nonLatinHashtagChars, 0x08a0, 0x08a0); // Arabic Extended A
  addCharsToCharClass(nonLatinHashtagChars, 0x08a2, 0x08ac);
  addCharsToCharClass(nonLatinHashtagChars, 0x08e4, 0x08fe);
  addCharsToCharClass(nonLatinHashtagChars, 0xfb50, 0xfbb1); // Arabic Pres. Forms A
  addCharsToCharClass(nonLatinHashtagChars, 0xfbd3, 0xfd3d);
  addCharsToCharClass(nonLatinHashtagChars, 0xfd50, 0xfd8f);
  addCharsToCharClass(nonLatinHashtagChars, 0xfd92, 0xfdc7);
  addCharsToCharClass(nonLatinHashtagChars, 0xfdf0, 0xfdfb);
  addCharsToCharClass(nonLatinHashtagChars, 0xfe70, 0xfe74); // Arabic Pres. Forms B
  addCharsToCharClass(nonLatinHashtagChars, 0xfe76, 0xfefc);
  addCharsToCharClass(nonLatinHashtagChars, 0x200c, 0x200c); // Zero-Width Non-Joiner
  // Thai
  addCharsToCharClass(nonLatinHashtagChars, 0x0e01, 0x0e3a);
  addCharsToCharClass(nonLatinHashtagChars, 0x0e40, 0x0e4e);
  // Hangul (Korean)
  addCharsToCharClass(nonLatinHashtagChars, 0x1100, 0x11ff); // Hangul Jamo
  addCharsToCharClass(nonLatinHashtagChars, 0x3130, 0x3185); // Hangul Compatibility Jamo
  addCharsToCharClass(nonLatinHashtagChars, 0xA960, 0xA97F); // Hangul Jamo Extended-A
  addCharsToCharClass(nonLatinHashtagChars, 0xAC00, 0xD7AF); // Hangul Syllables
  addCharsToCharClass(nonLatinHashtagChars, 0xD7B0, 0xD7FF); // Hangul Jamo Extended-B
  addCharsToCharClass(nonLatinHashtagChars, 0xFFA1, 0xFFDC); // half-width Hangul
  // Japanese and Chinese
  addCharsToCharClass(nonLatinHashtagChars, 0x30A1, 0x30FA); // Katakana (full-width)
  addCharsToCharClass(nonLatinHashtagChars, 0x30FC, 0x30FE); // Katakana Chouon and iteration marks (full-width)
  addCharsToCharClass(nonLatinHashtagChars, 0xFF66, 0xFF9F); // Katakana (half-width)
  addCharsToCharClass(nonLatinHashtagChars, 0xFF70, 0xFF70); // Katakana Chouon (half-width)
  addCharsToCharClass(nonLatinHashtagChars, 0xFF10, 0xFF19); // \
  addCharsToCharClass(nonLatinHashtagChars, 0xFF21, 0xFF3A); //  - Latin (full-width)
  addCharsToCharClass(nonLatinHashtagChars, 0xFF41, 0xFF5A); // /
  addCharsToCharClass(nonLatinHashtagChars, 0x3041, 0x3096); // Hiragana
  addCharsToCharClass(nonLatinHashtagChars, 0x3099, 0x309E); // Hiragana voicing and iteration mark
  addCharsToCharClass(nonLatinHashtagChars, 0x3400, 0x4DBF); // Kanji (CJK Extension A)
  addCharsToCharClass(nonLatinHashtagChars, 0x4E00, 0x9FFF); // Kanji (Unified)
  // -- Disabled as it breaks the Regex.
  //addCharsToCharClass(nonLatinHashtagChars, 0x20000, 0x2A6DF); // Kanji (CJK Extension B)
  addCharsToCharClass(nonLatinHashtagChars, 0x2A700, 0x2B73F); // Kanji (CJK Extension C)
  addCharsToCharClass(nonLatinHashtagChars, 0x2B740, 0x2B81F); // Kanji (CJK Extension D)
  addCharsToCharClass(nonLatinHashtagChars, 0x2F800, 0x2FA1F); // Kanji (CJK supplement)
  addCharsToCharClass(nonLatinHashtagChars, 0x3003, 0x3003); // Kanji iteration mark
  addCharsToCharClass(nonLatinHashtagChars, 0x3005, 0x3005); // Kanji iteration mark
  addCharsToCharClass(nonLatinHashtagChars, 0x303B, 0x303B); // Han iteration mark

  ccc.str.twttr.txt.regexen.nonLatinHashtagChars = regexSupplant(nonLatinHashtagChars.join(""));

  var latinAccentChars = [];
  // Latin accented characters (subtracted 0xD7 from the range, it's a confusable multiplication sign. Looks like "x")
  addCharsToCharClass(latinAccentChars, 0x00c0, 0x00d6);
  addCharsToCharClass(latinAccentChars, 0x00d8, 0x00f6);
  addCharsToCharClass(latinAccentChars, 0x00f8, 0x00ff);
  // Latin Extended A and B
  addCharsToCharClass(latinAccentChars, 0x0100, 0x024f);
  // assorted IPA Extensions
  addCharsToCharClass(latinAccentChars, 0x0253, 0x0254);
  addCharsToCharClass(latinAccentChars, 0x0256, 0x0257);
  addCharsToCharClass(latinAccentChars, 0x0259, 0x0259);
  addCharsToCharClass(latinAccentChars, 0x025b, 0x025b);
  addCharsToCharClass(latinAccentChars, 0x0263, 0x0263);
  addCharsToCharClass(latinAccentChars, 0x0268, 0x0268);
  addCharsToCharClass(latinAccentChars, 0x026f, 0x026f);
  addCharsToCharClass(latinAccentChars, 0x0272, 0x0272);
  addCharsToCharClass(latinAccentChars, 0x0289, 0x0289);
  addCharsToCharClass(latinAccentChars, 0x028b, 0x028b);
  // Okina for Hawaiian (it *is* a letter character)
  addCharsToCharClass(latinAccentChars, 0x02bb, 0x02bb);
  // Combining diacritics
  addCharsToCharClass(latinAccentChars, 0x0300, 0x036f);
  // Latin Extended Additional
  addCharsToCharClass(latinAccentChars, 0x1e00, 0x1eff);
  ccc.str.twttr.txt.regexen.latinAccentChars = regexSupplant(latinAccentChars.join(""));

  // A hashtag must contain characters, numbers and underscores, but not all numbers.
  ccc.str.twttr.txt.regexen.hashSigns = /[#＃]/;
  ccc.str.twttr.txt.regexen.hashtagAlpha = regexSupplant(/[a-z_#{latinAccentChars}#{nonLatinHashtagChars}]/i);
  ccc.str.twttr.txt.regexen.hashtagAlphaNumeric = regexSupplant(/[a-z0-9_#{latinAccentChars}#{nonLatinHashtagChars}]/i);
  ccc.str.twttr.txt.regexen.endHashtagMatch = regexSupplant(/^(?:#{hashSigns}|:\/\/)/);
  ccc.str.twttr.txt.regexen.hashtagBoundary = regexSupplant(/(?:^|$|[^&a-z0-9_#{latinAccentChars}#{nonLatinHashtagChars}])/);
  ccc.str.twttr.txt.regexen.validHashtag = regexSupplant(/(#{hashtagBoundary})(#{hashSigns})(#{hashtagAlphaNumeric}*#{hashtagAlpha}#{hashtagAlphaNumeric}*)/gi);

  // Mention related regex collection
  ccc.str.twttr.txt.regexen.validMentionPrecedingChars = /(?:^|[^a-zA-Z0-9_!#$%&*@＠]|(?:rt|RT|rT|Rt):?)/;
  ccc.str.twttr.txt.regexen.atSigns = /[@＠]/;
  ccc.str.twttr.txt.regexen.validMentionOrList = regexSupplant(
    '(#{validMentionPrecedingChars})' +  // $1: Preceding character
    '(#{atSigns})' +                     // $2: At mark
    '([a-zA-Z0-9_]{1,20})' +             // $3: Screen name
    '(\/[a-zA-Z][a-zA-Z0-9_\-]{0,24})?'  // $4: List (optional)
  , 'g');
  ccc.str.twttr.txt.regexen.validReply = regexSupplant(/^(?:#{spaces})*#{atSigns}([a-zA-Z0-9_]{1,20})/);
  ccc.str.twttr.txt.regexen.endMentionMatch = regexSupplant(/^(?:#{atSigns}|[#{latinAccentChars}]|:\/\/)/);

  // URL related regex collection
  ccc.str.twttr.txt.regexen.validUrlPrecedingChars = regexSupplant(/(?:[^A-Za-z0-9@＠$#＃#{invalid_chars_group}]|^)/);
  ccc.str.twttr.txt.regexen.invalidUrlWithoutProtocolPrecedingChars = /[-_.\/]$/;
  ccc.str.twttr.txt.regexen.invalidDomainChars = stringSupplant("#{punct}#{spaces_group}#{invalid_chars_group}", ccc.str.twttr.txt.regexen);
  ccc.str.twttr.txt.regexen.validDomainChars = regexSupplant(/[^#{invalidDomainChars}]/);
  ccc.str.twttr.txt.regexen.validSubdomain = regexSupplant(/(?:(?:#{validDomainChars}(?:[_-]|#{validDomainChars})*)?#{validDomainChars}\.)/);
  ccc.str.twttr.txt.regexen.validDomainName = regexSupplant(/(?:(?:#{validDomainChars}(?:-|#{validDomainChars})*)?#{validDomainChars}\.)/);
  ccc.str.twttr.txt.regexen.validGTLD = regexSupplant(RegExp(
    '(?:(?:academy|actor|aero|agency|arpa|asia|bar|bargains|berlin|best|bid|bike|biz|blue|boutique|build|builders|' +
    'buzz|cab|camera|camp|cards|careers|cat|catering|center|ceo|cheap|christmas|cleaning|clothing|club|codes|' +
    'coffee|com|community|company|computer|construction|contractors|cool|coop|cruises|dance|dating|democrat|' +
    'diamonds|directory|domains|edu|education|email|enterprises|equipment|estate|events|expert|exposed|farm|fish|' +
    'flights|florist|foundation|futbol|gallery|gift|glass|gov|graphics|guitars|guru|holdings|holiday|house|' +
    'immobilien|industries|info|institute|int|international|jobs|kaufen|kim|kitchen|kiwi|koeln|kred|land|lighting|' +
    'limo|link|luxury|management|mango|marketing|menu|mil|mobi|moda|monash|museum|nagoya|name|net|neustar|ninja|' +
    'okinawa|onl|org|partners|parts|photo|photography|photos|pics|pink|plumbing|post|pro|productions|properties|' +
    'pub|qpon|recipes|red|rentals|repair|report|reviews|rich|ruhr|sexy|shiksha|shoes|singles|social|solar|' +
    'solutions|supplies|supply|support|systems|tattoo|technology|tel|tienda|tips|today|tokyo|tools|training|' +
    'travel|uno|vacations|ventures|viajes|villas|vision|vote|voting|voto|voyage|wang|watch|wed|wien|wiki|works|' +
    'xxx|xyz|zone|дети|онлайн|орг|сайт|بازار|شبكة|みんな|中信|中文网|公司|公益|在线|我爱你|政务|游戏|移动|网络|集团|삼성)' +
    '(?=[^0-9a-zA-Z@]|$))'));
  ccc.str.twttr.txt.regexen.validCCTLD = regexSupplant(RegExp(
    '(?:(?:ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bl|bm|bn|bo|bq|br|bs|' +
    'bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cw|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|' +
    'et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|' +
    'im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|' +
    'me|mf|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|' +
    'pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|ss|st|su|sv|' +
    'sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|' +
    'ye|yt|za|zm|zw|мон|рф|срб|укр|қаз|الاردن|الجزائر|السعودية|المغرب|امارات|ایران|بھارت|تونس|سودان|سورية|عمان|فلسطين|قطر|مصر|مليسيا|پاکستان|' +
    'भारत|বাংলা|ভারত|ਭਾਰਤ|ભારત|இந்தியா|இலங்கை|சிங்கப்பூர்|భారత్|ලංකා|ไทย|გე|中国|中國|台湾|台灣|新加坡|' +
    '香港|한국)(?=[^0-9a-zA-Z@]|$))'));
  ccc.str.twttr.txt.regexen.validPunycode = regexSupplant(/(?:xn--[0-9a-z]+)/);
  ccc.str.twttr.txt.regexen.validDomain = regexSupplant(/(?:#{validSubdomain}*#{validDomainName}(?:#{validGTLD}|#{validCCTLD}|#{validPunycode}))/);
  ccc.str.twttr.txt.regexen.validAsciiDomain = regexSupplant(/(?:(?:[\-a-z0-9#{latinAccentChars}]+)\.)+(?:#{validGTLD}|#{validCCTLD}|#{validPunycode})/gi);
  ccc.str.twttr.txt.regexen.invalidShortDomain = regexSupplant(/^#{validDomainName}#{validCCTLD}$/i);

  ccc.str.twttr.txt.regexen.validPortNumber = regexSupplant(/[0-9]+/);

  ccc.str.twttr.txt.regexen.validGeneralUrlPathChars = regexSupplant(/[a-z0-9!\*';:=\+,\.\$\/%#\[\]\-_~@|&#{latinAccentChars}]/i);
  // Allow URL paths to contain up to two nested levels of balanced parens
  //  1. Used in Wikipedia URLs like /Primer_(film)
  //  2. Used in IIS sessions like /S(dfd346)/
  //  3. Used in Rdio URLs like /track/We_Up_(Album_Version_(Edited))/
  ccc.str.twttr.txt.regexen.validUrlBalancedParens = regexSupplant(
    '\\('                                   +
      '(?:'                                 +
        '#{validGeneralUrlPathChars}+'      +
        '|'                                 +
        // allow one nested level of balanced parentheses
        '(?:'                               +
          '#{validGeneralUrlPathChars}*'    +
          '\\('                             +
            '#{validGeneralUrlPathChars}+'  +
          '\\)'                             +
          '#{validGeneralUrlPathChars}*'    +
        ')'                                 +
      ')'                                   +
    '\\)'
  , 'i');
  // Valid end-of-path chracters (so /foo. does not gobble the period).
  // 1. Allow =&# for empty URL parameters and other URL-join artifacts
  ccc.str.twttr.txt.regexen.validUrlPathEndingChars = regexSupplant(/[\+\-a-z0-9=_#\/#{latinAccentChars}]|(?:#{validUrlBalancedParens})/i);
  // Allow @ in a url, but only in the middle. Catch things like http://example.com/@user/
  ccc.str.twttr.txt.regexen.validUrlPath = regexSupplant('(?:' +
    '(?:' +
      '#{validGeneralUrlPathChars}*' +
        '(?:#{validUrlBalancedParens}#{validGeneralUrlPathChars}*)*' +
        '#{validUrlPathEndingChars}'+
      ')|(?:@#{validGeneralUrlPathChars}+\/)'+
    ')', 'i');

  ccc.str.twttr.txt.regexen.validUrlQueryChars = /[a-z0-9!?\*'@\(\);:&=\+\$\/%#\[\]\-_\.,~|]/i;
  ccc.str.twttr.txt.regexen.validUrlQueryEndingChars = /[a-z0-9_&=#\/]/i;
  ccc.str.twttr.txt.regexen.extractUrl = regexSupplant(
    '('                                                            + // $1 total match
      '(#{validUrlPrecedingChars})'                                + // $2 Preceeding chracter
      '('                                                          + // $3 URL
        '(https?:\\/\\/)?'                                         + // $4 Protocol (optional)
        '(#{validDomain})'                                         + // $5 Domain(s)
        '(?::(#{validPortNumber}))?'                               + // $6 Port number (optional)
        '(\\/#{validUrlPath}*)?'                                   + // $7 URL Path
        '(\\?#{validUrlQueryChars}*#{validUrlQueryEndingChars})?'  + // $8 Query String
      ')'                                                          +
    ')'
  , 'gi');

  ccc.str.twttr.txt.regexen.validTcoUrl = /^https?:\/\/t\.co\/[a-z0-9]+/i;
  ccc.str.twttr.txt.regexen.urlHasProtocol = /^https?:\/\//i;
  ccc.str.twttr.txt.regexen.urlHasHttps = /^https:\/\//i;

  // cashtag related regex
  ccc.str.twttr.txt.regexen.cashtag = /[a-z]{1,6}(?:[._][a-z]{1,2})?/i;
  ccc.str.twttr.txt.regexen.validCashtag = regexSupplant('(^|#{spaces})(\\$)(#{cashtag})(?=$|\\s|[#{punct}])', 'gi');

  // These URL validation pattern strings are based on the ABNF from RFC 3986
  ccc.str.twttr.txt.regexen.validateUrlUnreserved = /[a-z0-9\-._~]/i;
  ccc.str.twttr.txt.regexen.validateUrlPctEncoded = /(?:%[0-9a-f]{2})/i;
  ccc.str.twttr.txt.regexen.validateUrlSubDelims = /[!$&'()*+,;=]/i;
  ccc.str.twttr.txt.regexen.validateUrlPchar = regexSupplant('(?:' +
    '#{validateUrlUnreserved}|' +
    '#{validateUrlPctEncoded}|' +
    '#{validateUrlSubDelims}|' +
    '[:|@]' +
  ')', 'i');

  ccc.str.twttr.txt.regexen.validateUrlScheme = /(?:[a-z][a-z0-9+\-.]*)/i;
  ccc.str.twttr.txt.regexen.validateUrlUserinfo = regexSupplant('(?:' +
    '#{validateUrlUnreserved}|' +
    '#{validateUrlPctEncoded}|' +
    '#{validateUrlSubDelims}|' +
    ':' +
  ')*', 'i');

  ccc.str.twttr.txt.regexen.validateUrlDecOctet = /(?:[0-9]|(?:[1-9][0-9])|(?:1[0-9]{2})|(?:2[0-4][0-9])|(?:25[0-5]))/i;
  ccc.str.twttr.txt.regexen.validateUrlIpv4 = regexSupplant(/(?:#{validateUrlDecOctet}(?:\.#{validateUrlDecOctet}){3})/i);

  // Punting on real IPv6 validation for now
  ccc.str.twttr.txt.regexen.validateUrlIpv6 = /(?:\[[a-f0-9:\.]+\])/i;

  // Also punting on IPvFuture for now
  ccc.str.twttr.txt.regexen.validateUrlIp = regexSupplant('(?:' +
    '#{validateUrlIpv4}|' +
    '#{validateUrlIpv6}' +
  ')', 'i');

  // This is more strict than the rfc specifies
  ccc.str.twttr.txt.regexen.validateUrlSubDomainSegment = /(?:[a-z0-9](?:[a-z0-9_\-]*[a-z0-9])?)/i;
  ccc.str.twttr.txt.regexen.validateUrlDomainSegment = /(?:[a-z0-9](?:[a-z0-9\-]*[a-z0-9])?)/i;
  ccc.str.twttr.txt.regexen.validateUrlDomainTld = /(?:[a-z](?:[a-z0-9\-]*[a-z0-9])?)/i;
  ccc.str.twttr.txt.regexen.validateUrlDomain = regexSupplant(/(?:(?:#{validateUrlSubDomainSegment]}\.)*(?:#{validateUrlDomainSegment]}\.)#{validateUrlDomainTld})/i);

  ccc.str.twttr.txt.regexen.validateUrlHost = regexSupplant('(?:' +
    '#{validateUrlIp}|' +
    '#{validateUrlDomain}' +
  ')', 'i');

  // Unencoded internationalized domains - this doesn't check for invalid UTF-8 sequences
  ccc.str.twttr.txt.regexen.validateUrlUnicodeSubDomainSegment = /(?:(?:[a-z0-9]|[^\u0000-\u007f])(?:(?:[a-z0-9_\-]|[^\u0000-\u007f])*(?:[a-z0-9]|[^\u0000-\u007f]))?)/i;
  ccc.str.twttr.txt.regexen.validateUrlUnicodeDomainSegment = /(?:(?:[a-z0-9]|[^\u0000-\u007f])(?:(?:[a-z0-9\-]|[^\u0000-\u007f])*(?:[a-z0-9]|[^\u0000-\u007f]))?)/i;
  ccc.str.twttr.txt.regexen.validateUrlUnicodeDomainTld = /(?:(?:[a-z]|[^\u0000-\u007f])(?:(?:[a-z0-9\-]|[^\u0000-\u007f])*(?:[a-z0-9]|[^\u0000-\u007f]))?)/i;
  ccc.str.twttr.txt.regexen.validateUrlUnicodeDomain = regexSupplant(/(?:(?:#{validateUrlUnicodeSubDomainSegment}\.)*(?:#{validateUrlUnicodeDomainSegment}\.)#{validateUrlUnicodeDomainTld})/i);

  ccc.str.twttr.txt.regexen.validateUrlUnicodeHost = regexSupplant('(?:' +
    '#{validateUrlIp}|' +
    '#{validateUrlUnicodeDomain}' +
  ')', 'i');

  ccc.str.twttr.txt.regexen.validateUrlPort = /[0-9]{1,5}/;

  ccc.str.twttr.txt.regexen.validateUrlUnicodeAuthority = regexSupplant(
    '(?:(#{validateUrlUserinfo})@)?'  + // $1 userinfo
    '(#{validateUrlUnicodeHost})'     + // $2 host
    '(?::(#{validateUrlPort}))?'        //$3 port
  , "i");

  ccc.str.twttr.txt.regexen.validateUrlAuthority = regexSupplant(
    '(?:(#{validateUrlUserinfo})@)?' + // $1 userinfo
    '(#{validateUrlHost})'           + // $2 host
    '(?::(#{validateUrlPort}))?'       // $3 port
  , "i");

  ccc.str.twttr.txt.regexen.validateUrlPath = regexSupplant(/(\/#{validateUrlPchar}*)*/i);
  ccc.str.twttr.txt.regexen.validateUrlQuery = regexSupplant(/(#{validateUrlPchar}|\/|\?)*/i);
  ccc.str.twttr.txt.regexen.validateUrlFragment = regexSupplant(/(#{validateUrlPchar}|\/|\?)*/i);

  // Modified version of RFC 3986 Appendix B
  ccc.str.twttr.txt.regexen.validateUrlUnencoded = regexSupplant(
    '^'                               + // Full URL
    '(?:'                             +
      '([^:/?#]+):\\/\\/'             + // $1 Scheme
    ')?'                              +
    '([^/?#]*)'                       + // $2 Authority
    '([^?#]*)'                        + // $3 Path
    '(?:'                             +
      '\\?([^#]*)'                    + // $4 Query
    ')?'                              +
    '(?:'                             +
      '#(.*)'                         + // $5 Fragment
    ')?$'
  , "i");


  // Default CSS class for auto-linked lists (along with the url class)
  var DEFAULT_LIST_CLASS = "tweet-url list-slug";
  // Default CSS class for auto-linked usernames (along with the url class)
  var DEFAULT_USERNAME_CLASS = "tweet-url username";
  // Default CSS class for auto-linked hashtags (along with the url class)
  var DEFAULT_HASHTAG_CLASS = "tweet-url hashtag";
  // Default CSS class for auto-linked cashtags (along with the url class)
  var DEFAULT_CASHTAG_CLASS = "tweet-url cashtag";
  // Options which should not be passed as HTML attributes
  var OPTIONS_NOT_ATTRIBUTES = {'urlClass':true, 'listClass':true, 'usernameClass':true, 'hashtagClass':true, 'cashtagClass':true,
                            'usernameUrlBase':true, 'listUrlBase':true, 'hashtagUrlBase':true, 'cashtagUrlBase':true,
                            'usernameUrlBlock':true, 'listUrlBlock':true, 'hashtagUrlBlock':true, 'linkUrlBlock':true,
                            'usernameIncludeSymbol':true, 'suppressLists':true, 'suppressNoFollow':true, 'targetBlank':true,
                            'suppressDataScreenName':true, 'urlEntities':true, 'symbolTag':true, 'textWithSymbolTag':true, 'urlTarget':true,
                            'invisibleTagAttrs':true, 'linkAttributeBlock':true, 'linkTextBlock': true, 'htmlEscapeNonEntities': true
                            };

  var BOOLEAN_ATTRIBUTES = {'disabled':true, 'readonly':true, 'multiple':true, 'checked':true};

  // Simple object cloning function for simple objects
  function clone(o) {
    var r = {};
    for (var k in o) {
      if (o.hasOwnProperty(k)) {
        r[k] = o[k];
      }
    }

    return r;
  }

  ccc.str.twttr.txt.tagAttrs = function(attributes) {
    var htmlAttrs = "";
    for (var k in attributes) {
      var v = attributes[k];
      if (BOOLEAN_ATTRIBUTES[k]) {
        v = v ? k : null;
      }
      if (v == null) continue;
      htmlAttrs += " " + ccc.str.twttr.txt.htmlEscape(k) + "=\"" + ccc.str.twttr.txt.htmlEscape(v.toString()) + "\"";
    }
    return htmlAttrs;
  };

  ccc.str.twttr.txt.linkToText = function(entity, text, attributes, options) {
    if (!options.suppressNoFollow) {
      attributes.rel = "nofollow";
    }
    // if linkAttributeBlock is specified, call it to modify the attributes
    if (options.linkAttributeBlock) {
      options.linkAttributeBlock(entity, attributes);
    }
    // if linkTextBlock is specified, call it to get a new/modified link text
    if (options.linkTextBlock) {
      text = options.linkTextBlock(entity, text);
    }
    var d = {
      text: text,
      attr: ccc.str.twttr.txt.tagAttrs(attributes)
    };
    return stringSupplant("<a#{attr}>#{text}</a>", d);
  };

  ccc.str.twttr.txt.linkToTextWithSymbol = function(entity, symbol, text, attributes, options) {
    var taggedSymbol = options.symbolTag ? "<" + options.symbolTag + ">" + symbol + "</"+ options.symbolTag + ">" : symbol;
    text = ccc.str.twttr.txt.htmlEscape(text);
    var taggedText = options.textWithSymbolTag ? "<" + options.textWithSymbolTag + ">" + text + "</"+ options.textWithSymbolTag + ">" : text;

    if (options.usernameIncludeSymbol || !symbol.match(ccc.str.twttr.txt.regexen.atSigns)) {
      return ccc.str.twttr.txt.linkToText(entity, taggedSymbol + taggedText, attributes, options);
    } else {
      return taggedSymbol + ccc.str.twttr.txt.linkToText(entity, taggedText, attributes, options);
    }
  };

  ccc.str.twttr.txt.linkToHashtag = function(entity, text, options) {
    var hash = text.substring(entity.indices[0], entity.indices[0] + 1);
    var hashtag = ccc.str.twttr.txt.htmlEscape(entity.hashtag);
    var attrs = clone(options.htmlAttrs || {});
    attrs.href = options.hashtagUrlBase + hashtag;
    attrs.title = "#" + hashtag;
    attrs["class"] = options.hashtagClass;
    if (hashtag.charAt(0).match(ccc.str.twttr.txt.regexen.rtl_chars)){
      attrs["class"] += " rtl";
    }
    if (options.targetBlank) {
      attrs.target = '_blank';
    }

    return ccc.str.twttr.txt.linkToTextWithSymbol(entity, hash, hashtag, attrs, options);
  };

  ccc.str.twttr.txt.linkToCashtag = function(entity, text, options) {
    var cashtag = ccc.str.twttr.txt.htmlEscape(entity.cashtag);
    var attrs = clone(options.htmlAttrs || {});
    attrs.href = options.cashtagUrlBase + cashtag;
    attrs.title = "$" + cashtag;
    attrs["class"] =  options.cashtagClass;
    if (options.targetBlank) {
      attrs.target = '_blank';
    }

    return ccc.str.twttr.txt.linkToTextWithSymbol(entity, "$", cashtag, attrs, options);
  };

  ccc.str.twttr.txt.linkToMentionAndList = function(entity, text, options) {
    var at = text.substring(entity.indices[0], entity.indices[0] + 1);
    var user = ccc.str.twttr.txt.htmlEscape(entity.screenName);
    var slashListname = ccc.str.twttr.txt.htmlEscape(entity.listSlug);
    var isList = entity.listSlug && !options.suppressLists;
    var attrs = clone(options.htmlAttrs || {});
    attrs["class"] = (isList ? options.listClass : options.usernameClass);
    attrs.href = isList ? options.listUrlBase + user + slashListname : options.usernameUrlBase + user;
    if (!isList && !options.suppressDataScreenName) {
      attrs['data-screen-name'] = user;
    }
    if (options.targetBlank) {
      attrs.target = '_blank';
    }

    return ccc.str.twttr.txt.linkToTextWithSymbol(entity, at, isList ? user + slashListname : user, attrs, options);
  };

  ccc.str.twttr.txt.linkToUrl = function(entity, text, options) {
    var url = entity.url;
    var displayUrl = url;
    var linkText = ccc.str.twttr.txt.htmlEscape(displayUrl);

    // If the caller passed a urlEntities object (provided by a Twitter API
    // response with include_entities=true), we use that to render the display_url
    // for each URL instead of it's underlying t.co URL.
    var urlEntity = (options.urlEntities && options.urlEntities[url]) || entity;
    if (urlEntity.display_url) {
      linkText = ccc.str.twttr.txt.linkTextWithEntity(urlEntity, options);
    }

    var attrs = clone(options.htmlAttrs || {});

    if (!url.match(ccc.str.twttr.txt.regexen.urlHasProtocol)) {
      url = "http://" + url;
    }
    attrs.href = url;

    if (options.targetBlank) {
      attrs.target = '_blank';
    }

    // set class only if urlClass is specified.
    if (options.urlClass) {
      attrs["class"] = options.urlClass;
    }

    // set target only if urlTarget is specified.
    if (options.urlTarget) {
      attrs.target = options.urlTarget;
    }

    if (!options.title && urlEntity.display_url) {
      attrs.title = urlEntity.expanded_url;
    }

    return ccc.str.twttr.txt.linkToText(entity, linkText, attrs, options);
  };

  ccc.str.twttr.txt.linkTextWithEntity = function (entity, options) {
    var displayUrl = entity.display_url;
    var expandedUrl = entity.expanded_url;

    // Goal: If a user copies and pastes a tweet containing t.co'ed link, the resulting paste
    // should contain the full original URL (expanded_url), not the display URL.
    //
    // Method: Whenever possible, we actually emit HTML that contains expanded_url, and use
    // font-size:0 to hide those parts that should not be displayed (because they are not part of display_url).
    // Elements with font-size:0 get copied even though they are not visible.
    // Note that display:none doesn't work here. Elements with display:none don't get copied.
    //
    // Additionally, we want to *display* ellipses, but we don't want them copied.  To make this happen we
    // wrap the ellipses in a tco-ellipsis class and provide an onCopy handler that sets display:none on
    // everything with the tco-ellipsis class.
    //
    // Exception: pic.twitter.com images, for which expandedUrl = "https://twitter.com/#!/username/status/1234/photo/1
    // For those URLs, display_url is not a substring of expanded_url, so we don't do anything special to render the elided parts.
    // For a pic.twitter.com URL, the only elided part will be the "https://", so this is fine.

    var displayUrlSansEllipses = displayUrl.replace(/…/g, ""); // We have to disregard ellipses for matching
    // Note: we currently only support eliding parts of the URL at the beginning or the end.
    // Eventually we may want to elide parts of the URL in the *middle*.  If so, this code will
    // become more complicated.  We will probably want to create a regexp out of display URL,
    // replacing every ellipsis with a ".*".
    if (expandedUrl.indexOf(displayUrlSansEllipses) != -1) {
      var displayUrlIndex = expandedUrl.indexOf(displayUrlSansEllipses);
      var v = {
        displayUrlSansEllipses: displayUrlSansEllipses,
        // Portion of expandedUrl that precedes the displayUrl substring
        beforeDisplayUrl: expandedUrl.substr(0, displayUrlIndex),
        // Portion of expandedUrl that comes after displayUrl
        afterDisplayUrl: expandedUrl.substr(displayUrlIndex + displayUrlSansEllipses.length),
        precedingEllipsis: displayUrl.match(/^…/) ? "…" : "",
        followingEllipsis: displayUrl.match(/…$/) ? "…" : ""
      };
      for (var k in v) {
        if (v.hasOwnProperty(k)) {
          v[k] = ccc.str.twttr.txt.htmlEscape(v[k]);
        }
      }
      // As an example: The user tweets "hi http://longdomainname.com/foo"
      // This gets shortened to "hi http://t.co/xyzabc", with display_url = "…nname.com/foo"
      // This will get rendered as:
      // <span class='tco-ellipsis'> <!-- This stuff should get displayed but not copied -->
      //   …
      //   <!-- There's a chance the onCopy event handler might not fire. In case that happens,
      //        we include an &nbsp; here so that the … doesn't bump up against the URL and ruin it.
      //        The &nbsp; is inside the tco-ellipsis span so that when the onCopy handler *does*
      //        fire, it doesn't get copied.  Otherwise the copied text would have two spaces in a row,
      //        e.g. "hi  http://longdomainname.com/foo".
      //   <span style='font-size:0'>&nbsp;</span>
      // </span>
      // <span style='font-size:0'>  <!-- This stuff should get copied but not displayed -->
      //   http://longdomai
      // </span>
      // <span class='js-display-url'> <!-- This stuff should get displayed *and* copied -->
      //   nname.com/foo
      // </span>
      // <span class='tco-ellipsis'> <!-- This stuff should get displayed but not copied -->
      //   <span style='font-size:0'>&nbsp;</span>
      //   …
      // </span>
      v['invisible'] = options.invisibleTagAttrs;
      return stringSupplant("<span class='tco-ellipsis'>#{precedingEllipsis}<span #{invisible}>&nbsp;</span></span><span #{invisible}>#{beforeDisplayUrl}</span><span class='js-display-url'>#{displayUrlSansEllipses}</span><span #{invisible}>#{afterDisplayUrl}</span><span class='tco-ellipsis'><span #{invisible}>&nbsp;</span>#{followingEllipsis}</span>", v);
    }
    return displayUrl;
  };

  ccc.str.twttr.txt.autoLinkEntities = function(text, entities, options) {
    options = clone(options || {});

    options.hashtagClass = options.hashtagClass || DEFAULT_HASHTAG_CLASS;
    options.hashtagUrlBase = options.hashtagUrlBase || "https://twitter.com/#!/search?q=%23";
    options.cashtagClass = options.cashtagClass || DEFAULT_CASHTAG_CLASS;
    options.cashtagUrlBase = options.cashtagUrlBase || "https://twitter.com/#!/search?q=%24";
    options.listClass = options.listClass || DEFAULT_LIST_CLASS;
    options.usernameClass = options.usernameClass || DEFAULT_USERNAME_CLASS;
    options.usernameUrlBase = options.usernameUrlBase || "https://twitter.com/";
    options.listUrlBase = options.listUrlBase || "https://twitter.com/";
    options.htmlAttrs = ccc.str.twttr.txt.extractHtmlAttrsFromOptions(options);
    options.invisibleTagAttrs = options.invisibleTagAttrs || "style='position:absolute;left:-9999px;'";

    // remap url entities to hash
    var urlEntities, i, len;
    if(options.urlEntities) {
      urlEntities = {};
      for(i = 0, len = options.urlEntities.length; i < len; i++) {
        urlEntities[options.urlEntities[i].url] = options.urlEntities[i];
      }
      options.urlEntities = urlEntities;
    }

    var result = "";
    var beginIndex = 0;

    // sort entities by start index
    entities.sort(function(a,b){ return a.indices[0] - b.indices[0]; });

    var nonEntity = options.htmlEscapeNonEntities ? ccc.str.twttr.txt.htmlEscape : function(text) {
      return text;
    };

    for (var i = 0; i < entities.length; i++) {
      var entity = entities[i];
      result += nonEntity(text.substring(beginIndex, entity.indices[0]));

      if (entity.url) {
        result += ccc.str.twttr.txt.linkToUrl(entity, text, options);
      } else if (entity.hashtag) {
        result += ccc.str.twttr.txt.linkToHashtag(entity, text, options);
      } else if (entity.screenName) {
        result += ccc.str.twttr.txt.linkToMentionAndList(entity, text, options);
      } else if (entity.cashtag) {
        result += ccc.str.twttr.txt.linkToCashtag(entity, text, options);
      }
      beginIndex = entity.indices[1];
    }
    result += nonEntity(text.substring(beginIndex, text.length));
    return result;
  };

  ccc.str.twttr.txt.autoLinkWithJSON = function(text, json, options) {
    // map JSON entity to twitter-text entity
    if (json.user_mentions) {
      for (var i = 0; i < json.user_mentions.length; i++) {
        // this is a @mention
        json.user_mentions[i].screenName = json.user_mentions[i].screen_name;
      }
    }
    
    if (json.hashtags) {
      for (var i = 0; i < json.hashtags.length; i++) {
        // this is a #hashtag
        json.hashtags[i].hashtag = json.hashtags[i].text;
      }
    }
    
    if (json.symbols) {
      for (var i = 0; i < json.symbols.length; i++) {
        // this is a $CASH tag
        json.symbols[i].cashtag = json.symbols[i].text;
      }
    }
    
    // concatenate all entities
    var entities = [];
    for (var key in json) {
      entities = entities.concat(json[key]);
    }

    // modify indices to UTF-16
    ccc.str.twttr.txt.modifyIndicesFromUnicodeToUTF16(text, entities);

    return ccc.str.twttr.txt.autoLinkEntities(text, entities, options);
  };

  ccc.str.twttr.txt.extractHtmlAttrsFromOptions = function(options) {
    var htmlAttrs = {};
    for (var k in options) {
      var v = options[k];
      if (OPTIONS_NOT_ATTRIBUTES[k]) continue;
      if (BOOLEAN_ATTRIBUTES[k]) {
        v = v ? k : null;
      }
      if (v == null) continue;
      htmlAttrs[k] = v;
    }
    return htmlAttrs;
  };

  ccc.str.twttr.txt.autoLink = function(text, options) {
    var entities = ccc.str.twttr.txt.extractEntitiesWithIndices(text, {extractUrlsWithoutProtocol: false});
    return ccc.str.twttr.txt.autoLinkEntities(text, entities, options);
  };

  ccc.str.twttr.txt.autoLinkUsernamesOrLists = function(text, options) {
    var entities = ccc.str.twttr.txt.extractMentionsOrListsWithIndices(text);
    return ccc.str.twttr.txt.autoLinkEntities(text, entities, options);
  };

  ccc.str.twttr.txt.autoLinkHashtags = function(text, options) {
    var entities = ccc.str.twttr.txt.extractHashtagsWithIndices(text);
    return ccc.str.twttr.txt.autoLinkEntities(text, entities, options);
  };

  ccc.str.twttr.txt.autoLinkCashtags = function(text, options) {
    var entities = ccc.str.twttr.txt.extractCashtagsWithIndices(text);
    return ccc.str.twttr.txt.autoLinkEntities(text, entities, options);
  };

  ccc.str.twttr.txt.autoLinkUrlsCustom = function(text, options) {
    var entities = ccc.str.twttr.txt.extractUrlsWithIndices(text, {extractUrlsWithoutProtocol: false});
    return ccc.str.twttr.txt.autoLinkEntities(text, entities, options);
  };

  ccc.str.twttr.txt.removeOverlappingEntities = function(entities) {
    entities.sort(function(a,b){ return a.indices[0] - b.indices[0]; });

    var prev = entities[0];
    for (var i = 1; i < entities.length; i++) {
      if (prev.indices[1] > entities[i].indices[0]) {
        entities.splice(i, 1);
        i--;
      } else {
        prev = entities[i];
      }
    }
  };

  ccc.str.twttr.txt.extractEntitiesWithIndices = function(text, options) {
    var entities = ccc.str.twttr.txt.extractUrlsWithIndices(text, options)
                    .concat(ccc.str.twttr.txt.extractMentionsOrListsWithIndices(text))
                    .concat(ccc.str.twttr.txt.extractHashtagsWithIndices(text, {checkUrlOverlap: false}))
                    .concat(ccc.str.twttr.txt.extractCashtagsWithIndices(text));

    if (entities.length == 0) {
      return [];
    }

    ccc.str.twttr.txt.removeOverlappingEntities(entities);
    return entities;
  };

  ccc.str.twttr.txt.extractMentions = function(text) {
    var screenNamesOnly = [],
        screenNamesWithIndices = ccc.str.twttr.txt.extractMentionsWithIndices(text);

    for (var i = 0; i < screenNamesWithIndices.length; i++) {
      var screenName = screenNamesWithIndices[i].screenName;
      screenNamesOnly.push(screenName);
    }

    return screenNamesOnly;
  };

  ccc.str.twttr.txt.extractMentionsWithIndices = function(text) {
    var mentions = [],
        mentionOrList,
        mentionsOrLists = ccc.str.twttr.txt.extractMentionsOrListsWithIndices(text);

    for (var i = 0 ; i < mentionsOrLists.length; i++) {
      mentionOrList = mentionsOrLists[i];
      if (mentionOrList.listSlug == '') {
        mentions.push({
          screenName: mentionOrList.screenName,
          indices: mentionOrList.indices
        });
      }
    }

    return mentions;
  };

  /**
   * Extract list or user mentions.
   * (Presence of listSlug indicates a list)
   */
  ccc.str.twttr.txt.extractMentionsOrListsWithIndices = function(text) {
    if (!text || !text.match(ccc.str.twttr.txt.regexen.atSigns)) {
      return [];
    }

    var possibleNames = [],
        slashListname;

    text.replace(ccc.str.twttr.txt.regexen.validMentionOrList, function(match, before, atSign, screenName, slashListname, offset, chunk) {
      var after = chunk.slice(offset + match.length);
      if (!after.match(ccc.str.twttr.txt.regexen.endMentionMatch)) {
        slashListname = slashListname || '';
        var startPosition = offset + before.length;
        var endPosition = startPosition + screenName.length + slashListname.length + 1;
        possibleNames.push({
          screenName: screenName,
          listSlug: slashListname,
          indices: [startPosition, endPosition]
        });
      }
    });

    return possibleNames;
  };


  ccc.str.twttr.txt.extractReplies = function(text) {
    if (!text) {
      return null;
    }

    var possibleScreenName = text.match(ccc.str.twttr.txt.regexen.validReply);
    if (!possibleScreenName ||
        RegExp.rightContext.match(ccc.str.twttr.txt.regexen.endMentionMatch)) {
      return null;
    }

    return possibleScreenName[1];
  };

  ccc.str.twttr.txt.extractUrls = function(text, options) {
    var urlsOnly = [],
        urlsWithIndices = ccc.str.twttr.txt.extractUrlsWithIndices(text, options);

    for (var i = 0; i < urlsWithIndices.length; i++) {
      urlsOnly.push(urlsWithIndices[i].url);
    }

    return urlsOnly;
  };

  ccc.str.twttr.txt.extractUrlsWithIndices = function(text, options) {
    if (!options) {
      options = {extractUrlsWithoutProtocol: true};
    }

    if (!text || (options.extractUrlsWithoutProtocol ? !text.match(/\./) : !text.match(/:/))) {
      return [];
    }

    var urls = [];

    while (ccc.str.twttr.txt.regexen.extractUrl.exec(text)) {
      var before = RegExp.$2, url = RegExp.$3, protocol = RegExp.$4, domain = RegExp.$5, path = RegExp.$7;
      var endPosition = ccc.str.twttr.txt.regexen.extractUrl.lastIndex,
          startPosition = endPosition - url.length;

      // if protocol is missing and domain contains non-ASCII characters,
      // extract ASCII-only domains.
      if (!protocol) {
        if (!options.extractUrlsWithoutProtocol
            || before.match(ccc.str.twttr.txt.regexen.invalidUrlWithoutProtocolPrecedingChars)) {
          continue;
        }
        var lastUrl = null,
            lastUrlInvalidMatch = false,
            asciiEndPosition = 0;
        domain.replace(ccc.str.twttr.txt.regexen.validAsciiDomain, function(asciiDomain) {
          var asciiStartPosition = domain.indexOf(asciiDomain, asciiEndPosition);
          asciiEndPosition = asciiStartPosition + asciiDomain.length;
          lastUrl = {
            url: asciiDomain,
            indices: [startPosition + asciiStartPosition, startPosition + asciiEndPosition]
          };
          lastUrlInvalidMatch = asciiDomain.match(ccc.str.twttr.txt.regexen.invalidShortDomain);
          if (!lastUrlInvalidMatch) {
            urls.push(lastUrl);
          }
        });

        // no ASCII-only domain found. Skip the entire URL.
        if (lastUrl == null) {
          continue;
        }

        // lastUrl only contains domain. Need to add path and query if they exist.
        if (path) {
          if (lastUrlInvalidMatch) {
            urls.push(lastUrl);
          }
          lastUrl.url = url.replace(domain, lastUrl.url);
          lastUrl.indices[1] = endPosition;
        }
      } else {
        // In the case of t.co URLs, don't allow additional path characters.
        if (url.match(ccc.str.twttr.txt.regexen.validTcoUrl)) {
          url = RegExp.lastMatch;
          endPosition = startPosition + url.length;
        }
        urls.push({
          url: url,
          indices: [startPosition, endPosition]
        });
      }
    }

    return urls;
  };

  ccc.str.twttr.txt.extractHashtags = function(text) {
    var hashtagsOnly = [],
        hashtagsWithIndices = ccc.str.twttr.txt.extractHashtagsWithIndices(text);

    for (var i = 0; i < hashtagsWithIndices.length; i++) {
      hashtagsOnly.push(hashtagsWithIndices[i].hashtag);
    }

    return hashtagsOnly;
  };

  ccc.str.twttr.txt.extractHashtagsWithIndices = function(text, options) {
    if (!options) {
      options = {checkUrlOverlap: true};
    }

    if (!text || !text.match(ccc.str.twttr.txt.regexen.hashSigns)) {
      return [];
    }

    var tags = [];

    text.replace(ccc.str.twttr.txt.regexen.validHashtag, function(match, before, hash, hashText, offset, chunk) {
      var after = chunk.slice(offset + match.length);
      if (after.match(ccc.str.twttr.txt.regexen.endHashtagMatch))
        return;
      var startPosition = offset + before.length;
      var endPosition = startPosition + hashText.length + 1;
      tags.push({
        hashtag: hashText,
        indices: [startPosition, endPosition]
      });
    });

    if (options.checkUrlOverlap) {
      // also extract URL entities
      var urls = ccc.str.twttr.txt.extractUrlsWithIndices(text);
      if (urls.length > 0) {
        var entities = tags.concat(urls);
        // remove overlap
        ccc.str.twttr.txt.removeOverlappingEntities(entities);
        // only push back hashtags
        tags = [];
        for (var i = 0; i < entities.length; i++) {
          if (entities[i].hashtag) {
            tags.push(entities[i]);
          }
        }
      }
    }

    return tags;
  };

  ccc.str.twttr.txt.extractCashtags = function(text) {
    var cashtagsOnly = [],
        cashtagsWithIndices = ccc.str.twttr.txt.extractCashtagsWithIndices(text);

    for (var i = 0; i < cashtagsWithIndices.length; i++) {
      cashtagsOnly.push(cashtagsWithIndices[i].cashtag);
    }

    return cashtagsOnly;
  };

  ccc.str.twttr.txt.extractCashtagsWithIndices = function(text) {
    if (!text || text.indexOf("$") == -1) {
      return [];
    }

    var tags = [];

    text.replace(ccc.str.twttr.txt.regexen.validCashtag, function(match, before, dollar, cashtag, offset, chunk) {
      var startPosition = offset + before.length;
      var endPosition = startPosition + cashtag.length + 1;
      tags.push({
        cashtag: cashtag,
        indices: [startPosition, endPosition]
      });
    });

    return tags;
  };

  ccc.str.twttr.txt.modifyIndicesFromUnicodeToUTF16 = function(text, entities) {
    ccc.str.twttr.txt.convertUnicodeIndices(text, entities, false);
  };

  ccc.str.twttr.txt.modifyIndicesFromUTF16ToUnicode = function(text, entities) {
    ccc.str.twttr.txt.convertUnicodeIndices(text, entities, true);
  };

  ccc.str.twttr.txt.getUnicodeTextLength = function(text) {
    return text.replace(ccc.str.twttr.txt.regexen.non_bmp_code_pairs, ' ').length;
  };

  ccc.str.twttr.txt.convertUnicodeIndices = function(text, entities, indicesInUTF16) {
    if (entities.length == 0) {
      return;
    }

    var charIndex = 0;
    var codePointIndex = 0;

    // sort entities by start index
    entities.sort(function(a,b){ return a.indices[0] - b.indices[0]; });
    var entityIndex = 0;
    var entity = entities[0];

    while (charIndex < text.length) {
      if (entity.indices[0] == (indicesInUTF16 ? charIndex : codePointIndex)) {
        var len = entity.indices[1] - entity.indices[0];
        entity.indices[0] = indicesInUTF16 ? codePointIndex : charIndex;
        entity.indices[1] = entity.indices[0] + len;

        entityIndex++;
        if (entityIndex == entities.length) {
          // no more entity
          break;
        }
        entity = entities[entityIndex];
      }

      var c = text.charCodeAt(charIndex);
      if (0xD800 <= c && c <= 0xDBFF && charIndex < text.length - 1) {
        // Found high surrogate char
        c = text.charCodeAt(charIndex + 1);
        if (0xDC00 <= c && c <= 0xDFFF) {
          // Found surrogate pair
          charIndex++;
        }
      }
      codePointIndex++;
      charIndex++;
    }
  };

  // this essentially does text.split(/<|>/)
  // except that won't work in IE, where empty strings are ommitted
  // so "<>".split(/<|>/) => [] in IE, but is ["", "", ""] in all others
  // but "<<".split("<") => ["", "", ""]
  ccc.str.twttr.txt.splitTags = function(text) {
    var firstSplits = text.split("<"),
        secondSplits,
        allSplits = [],
        split;

    for (var i = 0; i < firstSplits.length; i += 1) {
      split = firstSplits[i];
      if (!split) {
        allSplits.push("");
      } else {
        secondSplits = split.split(">");
        for (var j = 0; j < secondSplits.length; j += 1) {
          allSplits.push(secondSplits[j]);
        }
      }
    }

    return allSplits;
  };

  ccc.str.twttr.txt.hitHighlight = function(text, hits, options) {
    var defaultHighlightTag = "em";

    hits = hits || [];
    options = options || {};

    if (hits.length === 0) {
      return text;
    }

    var tagName = options.tag || defaultHighlightTag,
        tags = ["<" + tagName + ">", "</" + tagName + ">"],
        chunks = ccc.str.twttr.txt.splitTags(text),
        i,
        j,
        result = "",
        chunkIndex = 0,
        chunk = chunks[0],
        prevChunksLen = 0,
        chunkCursor = 0,
        startInChunk = false,
        chunkChars = chunk,
        flatHits = [],
        index,
        hit,
        tag,
        placed,
        hitSpot;

    for (i = 0; i < hits.length; i += 1) {
      for (j = 0; j < hits[i].length; j += 1) {
        flatHits.push(hits[i][j]);
      }
    }

    for (index = 0; index < flatHits.length; index += 1) {
      hit = flatHits[index];
      tag = tags[index % 2];
      placed = false;

      while (chunk != null && hit >= prevChunksLen + chunk.length) {
        result += chunkChars.slice(chunkCursor);
        if (startInChunk && hit === prevChunksLen + chunkChars.length) {
          result += tag;
          placed = true;
        }

        if (chunks[chunkIndex + 1]) {
          result += "<" + chunks[chunkIndex + 1] + ">";
        }

        prevChunksLen += chunkChars.length;
        chunkCursor = 0;
        chunkIndex += 2;
        chunk = chunks[chunkIndex];
        chunkChars = chunk;
        startInChunk = false;
      }

      if (!placed && chunk != null) {
        hitSpot = hit - prevChunksLen;
        result += chunkChars.slice(chunkCursor, hitSpot) + tag;
        chunkCursor = hitSpot;
        if (index % 2 === 0) {
          startInChunk = true;
        } else {
          startInChunk = false;
        }
      } else if(!placed) {
        placed = true;
        result += tag;
      }
    }

    if (chunk != null) {
      if (chunkCursor < chunkChars.length) {
        result += chunkChars.slice(chunkCursor);
      }
      for (index = chunkIndex + 1; index < chunks.length; index += 1) {
        result += (index % 2 === 0 ? chunks[index] : "<" + chunks[index] + ">");
      }
    }

    return result;
  };

  var MAX_LENGTH = 140;

  // Characters not allowed in Tweets
  var INVALID_CHARACTERS = [
    // BOM
    fromCode(0xFFFE),
    fromCode(0xFEFF),

    // Special
    fromCode(0xFFFF),

    // Directional Change
    fromCode(0x202A),
    fromCode(0x202B),
    fromCode(0x202C),
    fromCode(0x202D),
    fromCode(0x202E)
  ];

  // Returns the length of Tweet text with consideration to t.co URL replacement
  // and chars outside the basic multilingual plane that use 2 UTF16 code points
  ccc.str.twttr.txt.getTweetLength = function(text, options) {
    if (!options) {
      options = {
          // These come from https://api.twitter.com/1/help/configuration.json
          // described by https://dev.twitter.com/docs/api/1/get/help/configuration
          short_url_length: 22,
          short_url_length_https: 23
      };
    }
    var textLength = ccc.str.twttr.txt.getUnicodeTextLength(text),
        urlsWithIndices = ccc.str.twttr.txt.extractUrlsWithIndices(text);
    ccc.str.twttr.txt.modifyIndicesFromUTF16ToUnicode(text, urlsWithIndices);

    for (var i = 0; i < urlsWithIndices.length; i++) {
    	// Subtract the length of the original URL
      textLength += urlsWithIndices[i].indices[0] - urlsWithIndices[i].indices[1];

      // Add 23 characters for URL starting with https://
      // Otherwise add 22 characters
      if (urlsWithIndices[i].url.toLowerCase().match(ccc.str.twttr.txt.regexen.urlHasHttps)) {
         textLength += options.short_url_length_https;
      } else {
        textLength += options.short_url_length;
      }
    }

    return textLength;
  };

  // Check the text for any reason that it may not be valid as a Tweet. This is meant as a pre-validation
  // before posting to api.twitter.com. There are several server-side reasons for Tweets to fail but this pre-validation
  // will allow quicker feedback.
  //
  // Returns false if this text is valid. Otherwise one of the following strings will be returned:
  //
  //   "too_long": if the text is too long
  //   "empty": if the text is nil or empty
  //   "invalid_characters": if the text contains non-Unicode or any of the disallowed Unicode characters
  ccc.str.twttr.txt.isInvalidTweet = function(text) {
    if (!text) {
      return "empty";
    }

    // Determine max length independent of URL length
    if (ccc.str.twttr.txt.getTweetLength(text) > MAX_LENGTH) {
      return "too_long";
    }

    for (var i = 0; i < INVALID_CHARACTERS.length; i++) {
      if (text.indexOf(INVALID_CHARACTERS[i]) >= 0) {
        return "invalid_characters";
      }
    }

    return false;
  };

  ccc.str.twttr.txt.isValidTweetText = function(text) {
    return !ccc.str.twttr.txt.isInvalidTweet(text);
  };

  ccc.str.twttr.txt.isValidUsername = function(username) {
    if (!username) {
      return false;
    }

    var extracted = ccc.str.twttr.txt.extractMentions(username);

    // Should extract the username minus the @ sign, hence the .slice(1)
    return extracted.length === 1 && extracted[0] === username.slice(1);
  };

  var VALID_LIST_RE = regexSupplant(/^#{validMentionOrList}$/);

  ccc.str.twttr.txt.isValidList = function(usernameList) {
    var match = usernameList.match(VALID_LIST_RE);

    // Must have matched and had nothing before or after
    return !!(match && match[1] == "" && match[4]);
  };

  ccc.str.twttr.txt.isValidHashtag = function(hashtag) {
    if (!hashtag) {
      return false;
    }

    var extracted = ccc.str.twttr.txt.extractHashtags(hashtag);

    // Should extract the hashtag minus the # sign, hence the .slice(1)
    return extracted.length === 1 && extracted[0] === hashtag.slice(1);
  };

  ccc.str.twttr.txt.isValidUrl = function(url, unicodeDomains, requireProtocol) {
    if (unicodeDomains == null) {
      unicodeDomains = true;
    }

    if (requireProtocol == null) {
      requireProtocol = true;
    }

    if (!url) {
      return false;
    }

    var urlParts = url.match(ccc.str.twttr.txt.regexen.validateUrlUnencoded);

    if (!urlParts || urlParts[0] !== url) {
      return false;
    }

    var scheme = urlParts[1],
        authority = urlParts[2],
        path = urlParts[3],
        query = urlParts[4],
        fragment = urlParts[5];

    if (!(
      (!requireProtocol || (isValidMatch(scheme, ccc.str.twttr.txt.regexen.validateUrlScheme) && scheme.match(/^https?$/i))) &&
      isValidMatch(path, ccc.str.twttr.txt.regexen.validateUrlPath) &&
      isValidMatch(query, ccc.str.twttr.txt.regexen.validateUrlQuery, true) &&
      isValidMatch(fragment, ccc.str.twttr.txt.regexen.validateUrlFragment, true)
    )) {
      return false;
    }

    return (unicodeDomains && isValidMatch(authority, ccc.str.twttr.txt.regexen.validateUrlUnicodeAuthority)) ||
           (!unicodeDomains && isValidMatch(authority, ccc.str.twttr.txt.regexen.validateUrlAuthority));
  };

  function isValidMatch(string, regex, optional) {
    if (!optional) {
      // RegExp["$&"] is the text of the last match
      // blank strings are ok, but are falsy, so we check stringiness instead of truthiness
      return ((typeof string === "string") && string.match(regex) && RegExp["$&"] === string);
    }

    // RegExp["$&"] is the text of the last match
    return (!string || (string.match(regex) && RegExp["$&"] === string));
  }

  if (typeof module != 'undefined' && module.exports) {
    module.exports = ccc.str.twttr.txt;
  }

  if (typeof window != 'undefined') {
    if (window.ccc.str.twttr) {
      for (var prop in ccc.str.twttr) {
        window.ccc.str.twttr[prop] = ccc.str.twttr[prop];
      }
    } else {
      window.ccc.str.twttr = ccc.str.twttr;
    }
  }
})();


/* javascript EXIF Reader * 0.1.4 */


/* var EXIF = {}; * EXIF moved into global namespace ccc */

/* nested children for exif.js */
ccc.exi = ccc.exi || {};

ccc.exi.EXIF = ccc.exi.EXIF || {};

(function() {

var bDebug = false;

ccc.exi.EXIF.Tags = {

  // version tags
  0x9000 : "ExifVersion",     // EXIF version
  0xA000 : "FlashpixVersion",   // Flashpix format version

  // colorspace tags
  0xA001 : "ColorSpace",      // Color space information tag

  // image configuration
  0xA002 : "PixelXDimension",   // Valid width of meaningful image
  0xA003 : "PixelYDimension",   // Valid height of meaningful image
  0x9101 : "ComponentsConfiguration", // Information about channels
  0x9102 : "CompressedBitsPerPixel",  // Compressed bits per pixel

  // user information
  0x927C : "MakerNote",     // Any desired information written by the manufacturer
  0x9286 : "UserComment",     // Comments by user

  // related file
  0xA004 : "RelatedSoundFile",    // Name of related sound file

  // date and time
  0x9003 : "DateTimeOriginal",    // Date and time when the original image was generated
  0x9004 : "DateTimeDigitized",   // Date and time when the image was stored digitally
  0x9290 : "SubsecTime",      // Fractions of seconds for DateTime
  0x9291 : "SubsecTimeOriginal",    // Fractions of seconds for DateTimeOriginal
  0x9292 : "SubsecTimeDigitized",   // Fractions of seconds for DateTimeDigitized

  // picture-taking conditions
  0x829A : "ExposureTime",    // Exposure time (in seconds)
  0x829D : "FNumber",     // F number
  0x8822 : "ExposureProgram",   // Exposure program
  0x8824 : "SpectralSensitivity",   // Spectral sensitivity
  0x8827 : "ISOSpeedRatings",   // ISO speed rating
  0x8828 : "OECF",      // Optoelectric conversion factor
  0x9201 : "ShutterSpeedValue",   // Shutter speed
  0x9202 : "ApertureValue",   // Lens aperture
  0x9203 : "BrightnessValue",   // Value of brightness
  0x9204 : "ExposureBias",    // Exposure bias
  0x9205 : "MaxApertureValue",    // Smallest F number of lens
  0x9206 : "SubjectDistance",   // Distance to subject in meters
  0x9207 : "MeteringMode",    // Metering mode
  0x9208 : "LightSource",     // Kind of light source
  0x9209 : "Flash",     // Flash status
  0x9214 : "SubjectArea",     // Location and area of main subject
  0x920A : "FocalLength",     // Focal length of the lens in mm
  0xA20B : "FlashEnergy",     // Strobe energy in BCPS
  0xA20C : "SpatialFrequencyResponse",  // 
  0xA20E : "FocalPlaneXResolution",   // Number of pixels in width direction per FocalPlaneResolutionUnit
  0xA20F : "FocalPlaneYResolution",   // Number of pixels in height direction per FocalPlaneResolutionUnit
  0xA210 : "FocalPlaneResolutionUnit",  // Unit for measuring FocalPlaneXResolution and FocalPlaneYResolution
  0xA214 : "SubjectLocation",   // Location of subject in image
  0xA215 : "ExposureIndex",   // Exposure index selected on camera
  0xA217 : "SensingMethod",     // Image sensor type
  0xA300 : "FileSource",      // Image source (3 == DSC)
  0xA301 : "SceneType",       // Scene type (1 == directly photographed)
  0xA302 : "CFAPattern",      // Color filter array geometric pattern
  0xA401 : "CustomRendered",    // Special processing
  0xA402 : "ExposureMode",    // Exposure mode
  0xA403 : "WhiteBalance",    // 1 = auto white balance, 2 = manual
  0xA404 : "DigitalZoomRation",   // Digital zoom ratio
  0xA405 : "FocalLengthIn35mmFilm", // Equivalent foacl length assuming 35mm film camera (in mm)
  0xA406 : "SceneCaptureType",    // Type of scene
  0xA407 : "GainControl",     // Degree of overall image gain adjustment
  0xA408 : "Contrast",      // Direction of contrast processing applied by camera
  0xA409 : "Saturation",      // Direction of saturation processing applied by camera
  0xA40A : "Sharpness",     // Direction of sharpness processing applied by camera
  0xA40B : "DeviceSettingDescription",  // 
  0xA40C : "SubjectDistanceRange",  // Distance to subject

  // other tags
  0xA005 : "InteroperabilityIFDPointer",
  0xA420 : "ImageUniqueID"    // Identifier assigned uniquely to each image
};

ccc.exi.EXIF.TiffTags = {
  0x0100 : "ImageWidth",
  0x0101 : "ImageHeight",
  0x8769 : "ExifIFDPointer",
  0x8825 : "GPSInfoIFDPointer",
  0xA005 : "InteroperabilityIFDPointer",
  0x0102 : "BitsPerSample",
  0x0103 : "Compression",
  0x0106 : "PhotometricInterpretation",
  0x0112 : "Orientation",
  0x0115 : "SamplesPerPixel",
  0x011C : "PlanarConfiguration",
  0x0212 : "YCbCrSubSampling",
  0x0213 : "YCbCrPositioning",
  0x011A : "XResolution",
  0x011B : "YResolution",
  0x0128 : "ResolutionUnit",
  0x0111 : "StripOffsets",
  0x0116 : "RowsPerStrip",
  0x0117 : "StripByteCounts",
  0x0201 : "JPEGInterchangeFormat",
  0x0202 : "JPEGInterchangeFormatLength",
  0x012D : "TransferFunction",
  0x013E : "WhitePoint",
  0x013F : "PrimaryChromaticities",
  0x0211 : "YCbCrCoefficients",
  0x0214 : "ReferenceBlackWhite",
  0x0132 : "DateTime",
  0x010E : "ImageDescription",
  0x010F : "Make",
  0x0110 : "Model",
  0x0131 : "Software",
  0x013B : "Artist",
  0x8298 : "Copyright"
}

ccc.exi.EXIF.GPSTags = {
  0x0000 : "GPSVersionID",
  0x0001 : "GPSLatitudeRef",
  0x0002 : "GPSLatitude",
  0x0003 : "GPSLongitudeRef",
  0x0004 : "GPSLongitude",
  0x0005 : "GPSAltitudeRef",
  0x0006 : "GPSAltitude",
  0x0007 : "GPSTimeStamp",
  0x0008 : "GPSSatellites",
  0x0009 : "GPSStatus",
  0x000A : "GPSMeasureMode",
  0x000B : "GPSDOP",
  0x000C : "GPSSpeedRef",
  0x000D : "GPSSpeed",
  0x000E : "GPSTrackRef",
  0x000F : "GPSTrack",
  0x0010 : "GPSImgDirectionRef",
  0x0011 : "GPSImgDirection",
  0x0012 : "GPSMapDatum",
  0x0013 : "GPSDestLatitudeRef",
  0x0014 : "GPSDestLatitude",
  0x0015 : "GPSDestLongitudeRef",
  0x0016 : "GPSDestLongitude",
  0x0017 : "GPSDestBearingRef",
  0x0018 : "GPSDestBearing",
  0x0019 : "GPSDestDistanceRef",
  0x001A : "GPSDestDistance",
  0x001B : "GPSProcessingMethod",
  0x001C : "GPSAreaInformation",
  0x001D : "GPSDateStamp",
  0x001E : "GPSDifferential"
}

ccc.exi.EXIF.StringValues = {
  ExposureProgram : {
    0 : "Not defined",
    1 : "Manual",
    2 : "Normal program",
    3 : "Aperture priority",
    4 : "Shutter priority",
    5 : "Creative program",
    6 : "Action program",
    7 : "Portrait mode",
    8 : "Landscape mode"
  },
  MeteringMode : {
    0 : "Unknown",
    1 : "Average",
    2 : "CenterWeightedAverage",
    3 : "Spot",
    4 : "MultiSpot",
    5 : "Pattern",
    6 : "Partial",
    255 : "Other"
  },
  LightSource : {
    0 : "Unknown",
    1 : "Daylight",
    2 : "Fluorescent",
    3 : "Tungsten (incandescent light)",
    4 : "Flash",
    9 : "Fine weather",
    10 : "Cloudy weather",
    11 : "Shade",
    12 : "Daylight fluorescent (D 5700 - 7100K)",
    13 : "Day white fluorescent (N 4600 - 5400K)",
    14 : "Cool white fluorescent (W 3900 - 4500K)",
    15 : "White fluorescent (WW 3200 - 3700K)",
    17 : "Standard light A",
    18 : "Standard light B",
    19 : "Standard light C",
    20 : "D55",
    21 : "D65",
    22 : "D75",
    23 : "D50",
    24 : "ISO studio tungsten",
    255 : "Other"
  },
  Flash : {
    0x0000 : "Flash did not fire",
    0x0001 : "Flash fired",
    0x0005 : "Strobe return light not detected",
    0x0007 : "Strobe return light detected",
    0x0009 : "Flash fired, compulsory flash mode",
    0x000D : "Flash fired, compulsory flash mode, return light not detected",
    0x000F : "Flash fired, compulsory flash mode, return light detected",
    0x0010 : "Flash did not fire, compulsory flash mode",
    0x0018 : "Flash did not fire, auto mode",
    0x0019 : "Flash fired, auto mode",
    0x001D : "Flash fired, auto mode, return light not detected",
    0x001F : "Flash fired, auto mode, return light detected",
    0x0020 : "No flash function",
    0x0041 : "Flash fired, red-eye reduction mode",
    0x0045 : "Flash fired, red-eye reduction mode, return light not detected",
    0x0047 : "Flash fired, red-eye reduction mode, return light detected",
    0x0049 : "Flash fired, compulsory flash mode, red-eye reduction mode",
    0x004D : "Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected",
    0x004F : "Flash fired, compulsory flash mode, red-eye reduction mode, return light detected",
    0x0059 : "Flash fired, auto mode, red-eye reduction mode",
    0x005D : "Flash fired, auto mode, return light not detected, red-eye reduction mode",
    0x005F : "Flash fired, auto mode, return light detected, red-eye reduction mode"
  },
  SensingMethod : {
    1 : "Not defined",
    2 : "One-chip color area sensor",
    3 : "Two-chip color area sensor",
    4 : "Three-chip color area sensor",
    5 : "Color sequential area sensor",
    7 : "Trilinear sensor",
    8 : "Color sequential linear sensor"
  },
  SceneCaptureType : {
    0 : "Standard",
    1 : "Landscape",
    2 : "Portrait",
    3 : "Night scene"
  },
  SceneType : {
    1 : "Directly photographed"
  },
  CustomRendered : {
    0 : "Normal process",
    1 : "Custom process"
  },
  WhiteBalance : {
    0 : "Auto white balance",
    1 : "Manual white balance"
  },
  GainControl : {
    0 : "None",
    1 : "Low gain up",
    2 : "High gain up",
    3 : "Low gain down",
    4 : "High gain down"
  },
  Contrast : {
    0 : "Normal",
    1 : "Soft",
    2 : "Hard"
  },
  Saturation : {
    0 : "Normal",
    1 : "Low saturation",
    2 : "High saturation"
  },
  Sharpness : {
    0 : "Normal",
    1 : "Soft",
    2 : "Hard"
  },
  SubjectDistanceRange : {
    0 : "Unknown",
    1 : "Macro",
    2 : "Close view",
    3 : "Distant view"
  },
  FileSource : {
    3 : "DSC"
  },

  Components : {
    0 : "",
    1 : "Y",
    2 : "Cb",
    3 : "Cr",
    4 : "R",
    5 : "G",
    6 : "B"
  }
}

function addEvent(oElement, strEvent, fncHandler) 
{
  if (oElement.addEventListener) { 
    oElement.addEventListener(strEvent, fncHandler, false); 
  } else if (oElement.attachEvent) { 
    oElement.attachEvent("on" + strEvent, fncHandler); 
  }
}


function imageHasData(oImg) 
{
  return !!(oImg.exifdata);
}

function getImageData(oImg, fncCallback) 
{
  BinaryAjax(
    oImg.src,
    function(oHTTP) {
      var oEXIF = findEXIFinJPEG(oHTTP.binaryResponse);
      oImg.exifdata = oEXIF || {};
      if (fncCallback) fncCallback();
    }
  )
}

function findEXIFinJPEG(oFile) {
  var aMarkers = [];

  if(typeof oFile != 'undefined') {

  if (oFile.getByteAt(0) != 0xFF || oFile.getByteAt(1) != 0xD8) {
    return false; // not a valid jpeg
  }

  var iOffset = 2;
  var iLength = oFile.getLength();
  while (iOffset < iLength) {
    if (oFile.getByteAt(iOffset) != 0xFF) {
      if (bDebug) console.log("Not a valid marker at offset " + iOffset + ", found: " + oFile.getByteAt(iOffset));
      return false; // not a valid marker, something is wrong
    }

    var iMarker = oFile.getByteAt(iOffset+1);

    // we could implement handling for other markers here, 
    // but we're only looking for 0xFFE1 for EXIF data

    if (iMarker == 22400) {
      if (bDebug) console.log("Found 0xFFE1 marker");
      return readEXIFData(oFile, iOffset + 4, oFile.getShortAt(iOffset+2, true)-2);
      iOffset += 2 + oFile.getShortAt(iOffset+2, true);

    } else if (iMarker == 225) {
      // 0xE1 = Application-specific 1 (for EXIF)
      if (bDebug) console.log("Found 0xFFE1 marker");
      return readEXIFData(oFile, iOffset + 4, oFile.getShortAt(iOffset+2, true)-2);

    } else {
      iOffset += 2 + oFile.getShortAt(iOffset+2, true);
    }

  }

  }

}


function readTags(oFile, iTIFFStart, iDirStart, oStrings, bBigEnd) 
{
  var iEntries = oFile.getShortAt(iDirStart, bBigEnd);
  var oTags = {};
  for (var i=0;i<iEntries;i++) {
    var iEntryOffset = iDirStart + i*12 + 2;
    var strTag = oStrings[oFile.getShortAt(iEntryOffset, bBigEnd)];
    if (!strTag && bDebug) console.log("Unknown tag: " + oFile.getShortAt(iEntryOffset, bBigEnd));
    oTags[strTag] = readTagValue(oFile, iEntryOffset, iTIFFStart, iDirStart, bBigEnd);
  }
  return oTags;
}


function readTagValue(oFile, iEntryOffset, iTIFFStart, iDirStart, bBigEnd)
{
  var iType = oFile.getShortAt(iEntryOffset+2, bBigEnd);
  var iNumValues = oFile.getLongAt(iEntryOffset+4, bBigEnd);
  var iValueOffset = oFile.getLongAt(iEntryOffset+8, bBigEnd) + iTIFFStart;

  switch (iType) {
    case 1: // byte, 8-bit unsigned int
    case 7: // undefined, 8-bit byte, value depending on field
      if (iNumValues == 1) {
        return oFile.getByteAt(iEntryOffset + 8, bBigEnd);
      } else {
        var iValOffset = iNumValues > 4 ? iValueOffset : (iEntryOffset + 8);
        var aVals = [];
        for (var n=0;n<iNumValues;n++) {
          aVals[n] = oFile.getByteAt(iValOffset + n);
        }
        return aVals;
      }
      break;

    case 2: // ascii, 8-bit byte
      var iStringOffset = iNumValues > 4 ? iValueOffset : (iEntryOffset + 8);
      return oFile.getStringAt(iStringOffset, iNumValues-1);
      break;

    case 3: // short, 16 bit int
      if (iNumValues == 1) {
        return oFile.getShortAt(iEntryOffset + 8, bBigEnd);
      } else {
        var iValOffset = iNumValues > 2 ? iValueOffset : (iEntryOffset + 8);
        var aVals = [];
        for (var n=0;n<iNumValues;n++) {
          aVals[n] = oFile.getShortAt(iValOffset + 2*n, bBigEnd);
        }
        return aVals;
      }
      break;

    case 4: // long, 32 bit int
      if (iNumValues == 1) {
        return oFile.getLongAt(iEntryOffset + 8, bBigEnd);
      } else {
        var aVals = [];
        for (var n=0;n<iNumValues;n++) {
          aVals[n] = oFile.getLongAt(iValueOffset + 4*n, bBigEnd);
        }
        return aVals;
      }
      break;
    case 5: // rational = two long values, first is numerator, second is denominator
      if (iNumValues == 1) {
        return oFile.getLongAt(iValueOffset, bBigEnd) / oFile.getLongAt(iValueOffset+4, bBigEnd);
      } else {
        var aVals = [];
        for (var n=0;n<iNumValues;n++) {
          aVals[n] = oFile.getLongAt(iValueOffset + 8*n, bBigEnd) / oFile.getLongAt(iValueOffset+4 + 8*n, bBigEnd);
        }
        return aVals;
      }
      break;
    case 9: // slong, 32 bit signed int
      if (iNumValues == 1) {
        return oFile.getSLongAt(iEntryOffset + 8, bBigEnd);
      } else {
        var aVals = [];
        for (var n=0;n<iNumValues;n++) {
          aVals[n] = oFile.getSLongAt(iValueOffset + 4*n, bBigEnd);
        }
        return aVals;
      }
      break;
    case 10: // signed rational, two slongs, first is numerator, second is denominator
      if (iNumValues == 1) {
        return oFile.getSLongAt(iValueOffset, bBigEnd) / oFile.getSLongAt(iValueOffset+4, bBigEnd);
      } else {
        var aVals = [];
        for (var n=0;n<iNumValues;n++) {
          aVals[n] = oFile.getSLongAt(iValueOffset + 8*n, bBigEnd) / oFile.getSLongAt(iValueOffset+4 + 8*n, bBigEnd);
        }
        return aVals;
      }
      break;
  }
}


function readEXIFData(oFile, iStart, iLength) 
{
  if (oFile.getStringAt(iStart, 4) != "Exif") {
    if (bDebug) console.log("Not valid EXIF data! " + oFile.getStringAt(iStart, 4));
    return false;
  }

  var bBigEnd;

  var iTIFFOffset = iStart + 6;

  // test for TIFF validity and endianness
  if (oFile.getShortAt(iTIFFOffset) == 0x4949) {
    bBigEnd = false;
  } else if (oFile.getShortAt(iTIFFOffset) == 0x4D4D) {
    bBigEnd = true;
  } else {
    if (bDebug) console.log("Not valid TIFF data! (no 0x4949 or 0x4D4D)");
    return false;
  }

  if (oFile.getShortAt(iTIFFOffset+2, bBigEnd) != 0x002A) {
    if (bDebug) console.log("Not valid TIFF data! (no 0x002A)");
    return false;
  }

  if (oFile.getLongAt(iTIFFOffset+4, bBigEnd) != 0x00000008) {
    if (bDebug) console.log("Not valid TIFF data! (First offset not 8)", oFile.getShortAt(iTIFFOffset+4, bBigEnd));
    return false;
  }

  var oTags = readTags(oFile, iTIFFOffset, iTIFFOffset+8, ccc.exi.EXIF.TiffTags, bBigEnd);

  if (oTags.ExifIFDPointer) {
    var oEXIFTags = readTags(oFile, iTIFFOffset, iTIFFOffset + oTags.ExifIFDPointer, ccc.exi.EXIF.Tags, bBigEnd);
    for (var strTag in oEXIFTags) {
      switch (strTag) {
        case "LightSource" :
        case "Flash" :
        case "MeteringMode" :
        case "ExposureProgram" :
        case "SensingMethod" :
        case "SceneCaptureType" :
        case "SceneType" :
        case "CustomRendered" :
        case "WhiteBalance" : 
        case "GainControl" : 
        case "Contrast" :
        case "Saturation" :
        case "Sharpness" : 
        case "SubjectDistanceRange" :
        case "FileSource" :
          oEXIFTags[strTag] = ccc.exi.EXIF.StringValues[strTag][oEXIFTags[strTag]];
          break;
  
        case "ExifVersion" :
        case "FlashpixVersion" :
          oEXIFTags[strTag] = String.fromCharCode(oEXIFTags[strTag][0], oEXIFTags[strTag][1], oEXIFTags[strTag][2], oEXIFTags[strTag][3]);
          break;
  
        case "ComponentsConfiguration" : 
          oEXIFTags[strTag] = 
            ccc.exi.EXIF.StringValues.Components[oEXIFTags[strTag][0]]
            + ccc.exi.EXIF.StringValues.Components[oEXIFTags[strTag][1]]
            + ccc.exi.EXIF.StringValues.Components[oEXIFTags[strTag][2]]
            + ccc.exi.EXIF.StringValues.Components[oEXIFTags[strTag][3]];
          break;
      }
      oTags[strTag] = oEXIFTags[strTag];
    }
  }

  if (oTags.GPSInfoIFDPointer) {
    var oGPSTags = readTags(oFile, iTIFFOffset, iTIFFOffset + oTags.GPSInfoIFDPointer, ccc.exi.EXIF.GPSTags, bBigEnd);
    for (var strTag in oGPSTags) {
      switch (strTag) {
        case "GPSVersionID" : 
          oGPSTags[strTag] = oGPSTags[strTag][0] 
            + "." + oGPSTags[strTag][1] 
            + "." + oGPSTags[strTag][2] 
            + "." + oGPSTags[strTag][3];
          break;
      }
      oTags[strTag] = oGPSTags[strTag];
    }
  }

  return oTags;
}


ccc.exi.EXIF.getData = function(oImg, fncCallback) 
{
  if (!oImg.complete) return false;
  if (!imageHasData(oImg)) {
    getImageData(oImg, fncCallback);
  } else {
    if (fncCallback) fncCallback();
  }
  return true;
}

ccc.exi.EXIF.getTag = function(oImg, strTag) 
{
  if (!imageHasData(oImg)) return;
  return oImg.exifdata[strTag];
}

ccc.exi.EXIF.getAllTags = function(oImg) 
{
  if (!imageHasData(oImg)) return {};
  var oData = oImg.exifdata;
  var oAllTags = {};
  for (var a in oData) {
    if (oData.hasOwnProperty(a)) {
      oAllTags[a] = oData[a];
    }
  }
  return oAllTags;
}


ccc.exi.EXIF.pretty = function(oImg) 
{
  if (!imageHasData(oImg)) return "";
  var oData = oImg.exifdata;
  var strPretty = "";
  for (var a in oData) {
    if (oData.hasOwnProperty(a)) {
      if (typeof oData[a] == "object") {
        strPretty += a + " : [" + oData[a].length + " values]\r\n";
      } else {
        strPretty += a + " : " + oData[a] + "\r\n";
      }
    }
  }
  return strPretty;
}

ccc.exi.EXIF.readFromBinaryFile = function(oFile) {
  return findEXIFinJPEG(oFile);
}

function loadAllImages() 
{
  var aImages = document.getElementsByTagName("img");
  for (var i=0;i<aImages.length;i++) {
    if (aImages[i].getAttribute("exif") == "true") {
      if (!aImages[i].complete) {
        addEvent(aImages[i], "load", 
          function() {
            ccc.exi.EXIF.getData(this);
          }
        ); 
      } else {
        ccc.exi.EXIF.getData(aImages[i]);
      }
    }
  }
}

addEvent(window, "load", loadAllImages); 

})();


/* binary Ajax 0.1.10 */


var BinaryFile = function(strData, iDataOffset, iDataLength) {
  var data = strData;
  var dataOffset = iDataOffset || 0;
  var dataLength = 0;

  this.getRawData = function() {
    return data;
  }

  if (typeof strData == "string") {
    dataLength = iDataLength || data.length;

    this.getByteAt = function(iOffset) {
      return data.charCodeAt(iOffset + dataOffset) & 0xFF;
    }
    
    this.getBytesAt = function(iOffset, iLength) {
      var aBytes = [];
      
      for (var i = 0; i < iLength; i++) {
        aBytes[i] = data.charCodeAt((iOffset + i) + dataOffset) & 0xFF
      };
      
      return aBytes;
    }
  } else if (typeof strData == "unknown") {
    dataLength = iDataLength || IEBinary_getLength(data);

    this.getByteAt = function(iOffset) {
      return IEBinary_getByteAt(data, iOffset + dataOffset);
    }

    this.getBytesAt = function(iOffset, iLength) {
      return new VBArray(IEBinary_getBytesAt(data, iOffset + dataOffset, iLength)).toArray();
    }
  }

  this.getLength = function() {
    return dataLength;
  }

  this.getSByteAt = function(iOffset) {
    var iByte = this.getByteAt(iOffset);
    if (iByte > 127)
      return iByte - 256;
    else
      return iByte;
  }

  this.getShortAt = function(iOffset, bBigEndian) {
    var iShort = bBigEndian ? 
      (this.getByteAt(iOffset) << 8) + this.getByteAt(iOffset + 1)
      : (this.getByteAt(iOffset + 1) << 8) + this.getByteAt(iOffset)
    if (iShort < 0) iShort += 65536;
    return iShort;
  }
  this.getSShortAt = function(iOffset, bBigEndian) {
    var iUShort = this.getShortAt(iOffset, bBigEndian);
    if (iUShort > 32767)
      return iUShort - 65536;
    else
      return iUShort;
  }
  this.getLongAt = function(iOffset, bBigEndian) {
    var iByte1 = this.getByteAt(iOffset),
      iByte2 = this.getByteAt(iOffset + 1),
      iByte3 = this.getByteAt(iOffset + 2),
      iByte4 = this.getByteAt(iOffset + 3);

    var iLong = bBigEndian ? 
      (((((iByte1 << 8) + iByte2) << 8) + iByte3) << 8) + iByte4
      : (((((iByte4 << 8) + iByte3) << 8) + iByte2) << 8) + iByte1;
    if (iLong < 0) iLong += 4294967296;
    return iLong;
  }
  this.getSLongAt = function(iOffset, bBigEndian) {
    var iULong = this.getLongAt(iOffset, bBigEndian);
    if (iULong > 2147483647)
      return iULong - 4294967296;
    else
      return iULong;
  }

  this.getStringAt = function(iOffset, iLength) {
    var aStr = [];
    
    var aBytes = this.getBytesAt(iOffset, iLength);
    for (var j=0; j < iLength; j++) {
      aStr[j] = String.fromCharCode(aBytes[j]);
    }
    return aStr.join("");
  }
  
  this.getCharAt = function(iOffset) {
    return String.fromCharCode(this.getByteAt(iOffset));
  }
  this.toBase64 = function() {
    return window.btoa(data);
  }
  this.fromBase64 = function(strBase64) {
    data = window.atob(strBase64);
  }
}


var BinaryAjax = (function() {

  function createRequest() {
    var oHTTP = null;
    if (window.ActiveXObject) {
      oHTTP = new ActiveXObject("Microsoft.XMLHTTP");
    } else if (window.XMLHttpRequest) {
      oHTTP = new XMLHttpRequest();
    }
    return oHTTP;
  }

  function getHead(strURL, fncCallback, fncError) {
    var oHTTP = createRequest();
    if (oHTTP) {
      if (fncCallback) {
        if (typeof(oHTTP.onload) != "undefined") {
          oHTTP.onload = function() {
            if (oHTTP.status == "200") {
              fncCallback(this);
            } else {
              if (fncError) fncError();
            }
            oHTTP = null;
          };
        } else {
          oHTTP.onreadystatechange = function() {
            if (oHTTP.readyState == 4) {
              if (oHTTP.status == "200") {
                fncCallback(this);
              } else {
                if (fncError) fncError();
              }
              oHTTP = null;
            }
          };
        }
      }
      oHTTP.open("HEAD", strURL, true);
      oHTTP.send(null);
    } else {
      if (fncError) fncError();
    }
  }

  function sendRequest(strURL, fncCallback, fncError, aRange, bAcceptRanges, iFileSize) {
    var oHTTP = createRequest();
    if (oHTTP) {

      var iDataOffset = 0;
      if (aRange && !bAcceptRanges) {
        iDataOffset = aRange[0];
      }
      var iDataLen = 0;
      if (aRange) {
        iDataLen = aRange[1]-aRange[0]+1;
      }

      if (fncCallback) {
        if (typeof(oHTTP.onload) != "undefined") {
          oHTTP.onload = function() {
            if (oHTTP.status == "200" || oHTTP.status == "206" || oHTTP.status == "0") {
              oHTTP.binaryResponse = new BinaryFile(oHTTP.responseText, iDataOffset, iDataLen);
              oHTTP.fileSize = iFileSize || oHTTP.getResponseHeader("Content-Length");
              fncCallback(oHTTP);
            } else {
              if (fncError) fncError();
            }
            oHTTP = null;
          };
        } else {
          oHTTP.onreadystatechange = function() {
            if (oHTTP.readyState == 4) {
              if (oHTTP.status == "200" || oHTTP.status == "206" || oHTTP.status == "0") {
                // IE6 craps if we try to extend the XHR object
                var oRes = {
                  status : oHTTP.status,
                  // IE needs responseBody, Chrome/Safari needs responseText
                  binaryResponse : new BinaryFile(
                    typeof oHTTP.responseBody == "unknown" ? oHTTP.responseBody : oHTTP.responseText, iDataOffset, iDataLen
                  ),
                  fileSize : iFileSize || oHTTP.getResponseHeader("Content-Length")
                };
                fncCallback(oRes);
              } else {
                if (fncError) fncError();
              }
              oHTTP = null;
            }
          };
        }
      }
      oHTTP.open("GET", strURL, true);

      if (oHTTP.overrideMimeType) oHTTP.overrideMimeType('text/plain; charset=x-user-defined');

      if (aRange && bAcceptRanges) {
        oHTTP.setRequestHeader("Range", "bytes=" + aRange[0] + "-" + aRange[1]);
      }

      oHTTP.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 1970 00:00:00 GMT");

      oHTTP.send(null);
    } else {
      if (fncError) fncError();
    }
  }

  return function(strURL, fncCallback, fncError, aRange) {

    if (aRange) {
      getHead(
        strURL, 
        function(oHTTP) {
          var iLength = parseInt(oHTTP.getResponseHeader("Content-Length"),10);
          var strAcceptRanges = oHTTP.getResponseHeader("Accept-Ranges");

          var iStart, iEnd;
          iStart = aRange[0];
          if (aRange[0] < 0) 
            iStart += iLength;
          iEnd = iStart + aRange[1] - 1;

          sendRequest(strURL, fncCallback, fncError, [iStart, iEnd], (strAcceptRanges == "bytes"), iLength);
        }
      );

    } else {
      sendRequest(strURL, fncCallback, fncError);
    }
  }

}());

/*
document.write(
  "<script type='text/vbscript'>\r\n"
  + "Function IEBinary_getByteAt(strBinary, iOffset)\r\n"
  + " IEBinary_getByteAt = AscB(MidB(strBinary,iOffset+1,1))\r\n"
  + "End Function\r\n"
  + "Function IEBinary_getLength(strBinary)\r\n"
  + " IEBinary_getLength = LenB(strBinary)\r\n"
  + "End Function\r\n"
  + "</script>\r\n"
);
*/

document.write(
  "<script type='text/vbscript'>\r\n"
  + "Function IEBinary_getByteAt(strBinary, iOffset)\r\n"
  + " IEBinary_getByteAt = AscB(MidB(strBinary, iOffset + 1, 1))\r\n"
  + "End Function\r\n"
  + "Function IEBinary_getBytesAt(strBinary, iOffset, iLength)\r\n"
  + "  Dim aBytes()\r\n"
  + "  ReDim aBytes(iLength - 1)\r\n"
  + "  For i = 0 To iLength - 1\r\n"
  + "   aBytes(i) = IEBinary_getByteAt(strBinary, iOffset + i)\r\n"  
  + "  Next\r\n"
  + "  IEBinary_getBytesAt = aBytes\r\n" 
  + "End Function\r\n"
  + "Function IEBinary_getLength(strBinary)\r\n"
  + " IEBinary_getLength = LenB(strBinary)\r\n"
  + "End Function\r\n"
  + "</script>\r\n"
);