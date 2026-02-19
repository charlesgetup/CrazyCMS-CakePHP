<?php
App::uses('AutoKeyword', 'Seo');
App::uses('AutoDescription', 'Seo');

/**
 * 	This class is used to generate SEO related Meta tags.
 *
 * 	@param $encoding This is the content encoding
 * 	@param $params See below about param data structure
 *
 * 	e.g. $params = array(
 * 		     'content' 	=> {page content},
 * 			 'keywords' => array( // Here are the keyword generation params
 *			 	'min_word_length' 			=> 5,
 *				'min_word_occur' 			=> 2,
 *				'min_2words_length' 		=> 3,
 *				'min_2words_phrase_length' 	=> 10,
 *				'min_2words_phrase_occur' 	=> 2,
 *				'min_3words_length' 		=> 3,
 *				'min_3words_phrase_length' 	=> 10,
 *				'min_3words_phrase_occur' 	=> 2
 * 			 ),
 * 			 'description'  => array( // Here are the description generation params
 *				'removeTagsByCss' => array( {css selectors about the tags to remove} )
 * 			 ),
 * 			 'companyName'  	=> {company name},
 *  		 'companyAddress'  	=> array(
 *  			'all' 			=> {company address in one line},
 *  			'streetAddress' => {street address},
 *  			'state'			=> {state / province / region},
 *  			'postcode'		=> {postcode / zip code},
 *  			'country'		=> {country}
 *  		 ),
 *  		 'companyEmail'  	=> {company email},
 *   		 'companyPhone'  	=> {company phone},
 *    		 'companyDomain'  	=> {company domain},
 *           'companyLogo'  	=> array(
 *           	'url' 		=> {company logo url},
 *           	'width'		=> {company logo width},
 *           	'height' 	=> {company logo height}
 *           ),
 * 			 'pageURL'			=> {page URL},
 * 			 'pageTitle'		=> {page title},
 * 			 'pageCreateTime'	=> {create time, yyyy-mm-ddTHH:ii:ss+0000},
 * 			 'pageModifyTime'	=> {modify time, yyyy-mm-ddTHH:ii:ss+0000},
 * 			 'breadcrumbList'	=> {URL array, starts from home page},
 * 			 'socialMediaUrls'	=> {array of social media urls},
 * 			 'socialMediaPreviewImage' => array(
 * 				'url' 		=> {image url},
 * 				'type'		=> {image type, e.g. image/png},
 * 				'height' 	=> {image height},
 * 				'width'		=> {image width}
 * 			 ),
 * 			 'twitterUser'  => {twitter @username}
 * 		 )
 */

class GenerateMetaTag {

	private $template;

	private $encoding;

	private $content;

	private $keywords;

	private $description;

	private $language;

	private $locale;

	public function __construct($params, $encoding, $language = 'en', $locale = 'en_us'){

		$this->language = $language;
		$this->locale 	= $locale;

		// get parameters
		$this->encoding = $encoding;
		mb_internal_encoding($encoding);

		// generate keywords and description
		$keywordGeneratorParams = array_merge(array('content' => $params['content']), $params['keywords']);
		$this->keywords = new AutoKeyword($keywordGeneratorParams, $encoding);
		$keywordsTxt = $this->keywords->get_keywords();

		$this->description = new AutoDescription();
		$descriptionTxt = $this->description->getSummary($params, $encoding, $params['description']['removeTagsByCss']);

		// generate social media stuff
		$socialMediaUrlsJsonStr = json_encode($params['socialMediaUrls']);
		$socialMediaOgSeeAlsoTag = '';
		foreach($params['socialMediaUrls'] as $socialMediaUrl){
			$socialMediaOgSeeAlsoTag .= '<meta property="og:see_also" content="' .$socialMediaUrl .'" />';
		}

		$currentYear = date('Y');
		$currentDate = date('Y-m-d');
		$pageCopyrightYear = date('Y', strtotime($params['pageCreateTime']));

		$breadcrumbList = array();
		if(!empty($params['breadcrumbList']) && is_array($params['breadcrumbList'])){
			for($i = 0; $i < count($params['breadcrumbList']); $i++){
				$pos = $i + 1;
				$breadcrumbList[] = array(
					"@type" 	=> "ListItem",
					"position" 	=> "{$pos}",
					"item" => array(
						"@id" 	=> "{$params['breadcrumbList'][$i]}",
						"name" 	=> "{$params['breadcrumbList'][$i]}"
					)
				);
			}
		}
		$breadcrumbList = json_encode($breadcrumbList);

		$this->template = <<<TEMP

<!-- Standard SEO -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="referrer" content="always" />
<meta name="robots" content="all" />
<meta name="keywords" content="{$keywordsTxt}" />
<meta name="description" content="{$descriptionTxt}" />
<meta name="generator" content="CrazySoftSEO" />
<link rel="canonical" href="{$params['pageURL']}" />
<meta name="geo.region" content="Australia" />
<meta name="geo.placename" content="{$params['companyName']}" />

<!-- Dublin Core basic info -->

<meta name="dcterms.Identifier" content="{$params['pageURL']}" />
<meta name="dcterms.Format" content="text/html" />
<meta name="dcterms.Relation" content="{$params['companyName']}" />
<meta name="dcterms.Language" content="{$this->language}" />
<meta name="dcterms.Publisher" content="{$params['companyName']}" />
<meta name="dcterms.Type" content="text/html" />
<meta name="dcterms.Coverage" content="{$params['companyDomain']}" />
<meta name="dcterms.Rights" content="Copyright &copy;{$currentYear} {$params['companyName']}." />
<meta name="dcterms.Title" content="{$params['pageTitle']}" />
<meta name="dcterms.Subject" content="{$keywordsTxt}" />
<meta name="dcterms.Contributor" content="{$params['companyName']}" />
<meta name="dcterms.Date" content="{$currentDate}" />
<meta name="dcterms.Description" content="{$descriptionTxt}" />

<!-- Facebook OpenGraph (https://developers.facebook.com/docs/sharing/opengraph) -->

<meta property="og:type" content="website" />
<meta property="og:locale" content="{$this->locale}" />
<meta property="og:url" content="{$params['pageURL']}" />
<meta property="og:title" content="{$params['companyName']}" />
<meta property="og:description" content="{$descriptionTxt}" />
<meta property="og:image" content="{$params['socialMediaPreviewImage']['url']}" />
<meta property="og:image:type" content="{$params['socialMediaPreviewImage']['type']}" />
<meta property="og:image:width" content="{$params['socialMediaPreviewImage']['width']}" />
<meta property="og:image:height" content="{$params['socialMediaPreviewImage']['height']}" />
<meta property="og:site_name" content="{$params['companyName']}" />
{$socialMediaOgSeeAlsoTag}

<!-- Twitter Card -->

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{$params['companyName']}" />
<meta name="twitter:creator" content="{$params['twitterUser']}" />
<meta name="twitter:title" content="{$params['twitterUser']}" />
<meta name="twitter:description" content="{$descriptionTxt}" />
<meta name="twitter:image" content="{$params['socialMediaPreviewImage']['url']}" />

<!-- Identity -->

<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "Corporation",
    "name": "{$params['companyName']}",
    "url": "{$params['companyDomain']}",
    "sameAs": {$socialMediaUrlsJsonStr},
    "image": {
        "@type": "ImageObject",
        "url": "{$params['socialMediaPreviewImage']['url']}",
        "height": "{$params['socialMediaPreviewImage']['height']}",
        "width": "{$params['socialMediaPreviewImage']['width']}"
    },
    "telephone": "{$params['companyPhone']}",
    "email": "{$params['companyEmail']}",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "{$params['companyAddress']['streetAddress']}",
        "addressRegion": "{$params['companyAddress']['state']}",
        "postalCode": "{$params['companyAddress']['postcode']}",
        "addressCountry": "{$params['companyAddress']['country']}"
    },
    "logo": {
        "@type": "ImageObject",
        "url": "{$params['companyLogo']['url']}",
        "height": "{$params['companyLogo']['height']}",
        "width": "{$params['companyLogo']['width']}"
    },
    "location": {
        "@type": "Place",
        "name": "{$params['companyName']}",
        "telephone": "{$params['companyPhone']}",
        "image": {
            "@type": "ImageObject",
            "url": "{$params['socialMediaPreviewImage']['url']}",
            "height": "{$params['socialMediaPreviewImage']['height']}",
            "width": "{$params['socialMediaPreviewImage']['width']}"
        },
        "logo": {
            "@type": "ImageObject",
            "url": "{$params['companyLogo']['url']}",
            "height": "{$params['companyLogo']['height']}",
            "width": "{$params['companyLogo']['width']}"
        },
        "url": "{$params['companyDomain']}",
        "sameAs": {$socialMediaUrlsJsonStr},
        "address": {
            "@type": "PostalAddress",
	        "streetAddress": "{$params['companyAddress']['streetAddress']}",
	        "addressRegion": "{$params['companyAddress']['state']}",
	        "postalCode": "{$params['companyAddress']['postcode']}",
	        "addressCountry": "{$params['companyAddress']['country']}"
        }
    }
}
</script>

<!-- WebSite -->

<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "WebSite",
    "name": "{$params['companyName']}",
    "description": "{$descriptionTxt}",
    "url": "{$params['companyDomain']}",
    "image": "{$params['socialMediaPreviewImage']['url']}",
    "sameAs": {$socialMediaUrlsJsonStr},
    "copyrightHolder": {
        "@type": "Corporation",
        "name": "{$params['companyName']}",
        "url": "{$params['companyDomain']}",
        "sameAs": {$socialMediaUrlsJsonStr},
        "image": {
            "@type": "ImageObject",
            "url": "{$params['socialMediaPreviewImage']['url']}",
            "height": "{$params['socialMediaPreviewImage']['height']}",
            "width": "{$params['socialMediaPreviewImage']['width']}"
        },
        "telephone": "{$params['companyPhone']}",
        "email": "{$params['companyEmail']}",
        "address": {
            "@type": "PostalAddress",
	        "streetAddress": "{$params['companyAddress']['streetAddress']}",
	        "addressRegion": "{$params['companyAddress']['state']}",
	        "postalCode": "{$params['companyAddress']['postcode']}",
	        "addressCountry": "{$params['companyAddress']['country']}"
        },
        "logo": {
            "@type": "ImageObject",
            "url": "{$params['companyLogo']['url']}",
            "height": "{$params['companyLogo']['height']}",
            "width": "{$params['companyLogo']['width']}"
        },
        "location": {
            "@type": "Place",
            "name": "{$params['companyName']}",
            "telephone": "{$params['companyPhone']}",
            "image": {
                "@type": "ImageObject",
                "url": "{$params['socialMediaPreviewImage']['url']}",
                "height": "{$params['socialMediaPreviewImage']['height']}",
                "width": "{$params['socialMediaPreviewImage']['width']}"
            },
            "logo": {
                "@type": "ImageObject",
                "url": "{$params['companyLogo']['url']}",
                "height": "{$params['companyLogo']['height']}",
                "width": "{$params['companyLogo']['width']}"
            },
            "url": "{$params['companyDomain']}",
            "sameAs": {$socialMediaUrlsJsonStr},
            "address": {
                "@type": "PostalAddress",
		        "streetAddress": "{$params['companyAddress']['streetAddress']}",
		        "addressRegion": "{$params['companyAddress']['state']}",
		        "postalCode": "{$params['companyAddress']['postcode']}",
		        "addressCountry": "{$params['companyAddress']['country']}"
            }
        }
    },
    "author": {
        "@type": "Corporation",
        "name": "{$params['companyName']}",
        "url": "{$params['companyDomain']}",
        "sameAs": {$socialMediaUrlsJsonStr},
        "image": {
            "@type": "ImageObject",
            "url": "{$params['socialMediaPreviewImage']['url']}",
            "height": "{$params['socialMediaPreviewImage']['height']}",
            "width": "{$params['socialMediaPreviewImage']['width']}"
        },
        "telephone": "{$params['companyPhone']}",
        "email": "{$params['companyEmail']}",
        "address": {
            "@type": "PostalAddress",
	        "streetAddress": "{$params['companyAddress']['streetAddress']}",
	        "addressRegion": "{$params['companyAddress']['state']}",
	        "postalCode": "{$params['companyAddress']['postcode']}",
	        "addressCountry": "{$params['companyAddress']['country']}"
        },
        "logo": {
            "@type": "ImageObject",
            "url": "{$params['companyLogo']['url']}",
            "height": "{$params['companyLogo']['height']}",
            "width": "{$params['companyLogo']['width']}"
        },
        "location": {
            "@type": "Place",
            "name": "{$params['companyName']}",
            "telephone": "{$params['companyPhone']}",
            "image": {
                "@type": "ImageObject",
                "url": "{$params['socialMediaPreviewImage']['url']}",
                "height": "{$params['socialMediaPreviewImage']['height']}",
                "width": "{$params['socialMediaPreviewImage']['width']}"
            },
            "logo": {
                "@type": "ImageObject",
                "url": "{$params['companyLogo']['url']}",
                "height": "{$params['companyLogo']['height']}",
                "width": "{$params['companyLogo']['width']}"
            },
            "url": "{$params['companyDomain']}",
            "sameAs": {$socialMediaUrlsJsonStr},
            "address": {
                "@type": "PostalAddress",
		        "streetAddress": "{$params['companyAddress']['streetAddress']}",
		        "addressRegion": "{$params['companyAddress']['state']}",
		        "postalCode": "{$params['companyAddress']['postcode']}",
		        "addressCountry": "{$params['companyAddress']['country']}"
            }
        }
    },
    "creator": {
        "@type": "Organization"
    }
}
</script>

<!-- Place -->

<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "Place",
    "name": "{$params['companyName']}",
    "telephone": "{$params['companyPhone']}",
    "image": {
        "@type": "ImageObject",
        "url": "{$params['socialMediaPreviewImage']['url']}",
        "height": "{$params['socialMediaPreviewImage']['height']}",
        "width": "{$params['socialMediaPreviewImage']['width']}"
    },
    "logo": {
        "@type": "ImageObject",
        "url": "{$params['companyLogo']['url']}",
        "height": "{$params['companyLogo']['height']}",
        "width": "{$params['companyLogo']['width']}"
    },
    "url": "{$params['companyDomain']}",
    "sameAs": {$socialMediaUrlsJsonStr},
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "{$params['companyAddress']['streetAddress']}",
        "addressRegion": "{$params['companyAddress']['state']}",
        "postalCode": "{$params['companyAddress']['postcode']}",
        "addressCountry": "{$params['companyAddress']['country']}"
    }
}
</script>

<!-- Main Entity of Page -->

<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "WebPage",
    "name": "{$params['pageTitle']}",
    "description": "{$descriptionTxt}",
    "image": {
        "@type": "ImageObject",
        "url": "{$params['socialMediaPreviewImage']['url']}",
        "width": "{$params['socialMediaPreviewImage']['width']}",
        "height": "{$params['socialMediaPreviewImage']['height']}"
    },
    "url": "{$params['pageURL']}",
    "mainEntityOfPage": "{$params['pageURL']}",
    "inLanguage": "en_us",
    "headline": "{$params['pageTitle']}",
    "keywords": "{$keywordsTxt}",
    "dateCreated": "{$params['pageCreateTime']}",
    "dateModified": "{$params['pageModifyTime']}",
    "datePublished": "{$params['pageCreateTime']}",
    "copyrightYear": "{$pageCopyrightYear}",
    "author": {
        "@type": "Corporation",
        "name": "{$params['companyName']}",
        "url": "{$params['companyDomain']}",
        "sameAs": {$socialMediaUrlsJsonStr},
        "image": {
            "@type": "ImageObject",
            "url": "{$params['socialMediaPreviewImage']['url']}",
            "height": "{$params['socialMediaPreviewImage']['height']}",
            "width": "{$params['socialMediaPreviewImage']['width']}"
        },
        "telephone": "{$params['companyPhone']}",
        "email": "{$params['companyEmail']}",
        "address": {
            "@type": "PostalAddress",
	        "streetAddress": "{$params['companyAddress']['streetAddress']}",
	        "addressRegion": "{$params['companyAddress']['state']}",
	        "postalCode": "{$params['companyAddress']['postcode']}",
	        "addressCountry": "{$params['companyAddress']['country']}"
        },
        "logo": {
            "@type": "ImageObject",
            "url": "{$params['companyLogo']['url']}",
            "height": "{$params['companyLogo']['height']}",
            "width": "{$params['companyLogo']['width']}"
        },
        "location": {
            "@type": "Place",
            "name": "{$params['companyName']}",
            "telephone": "{$params['companyPhone']}",
            "image": {
                "@type": "ImageObject",
                "url": "{$params['socialMediaPreviewImage']['url']}",
                "height": "{$params['socialMediaPreviewImage']['height']}",
                "width": "{$params['socialMediaPreviewImage']['width']}"
            },
            "logo": {
                "@type": "ImageObject",
                "url": "{$params['companyLogo']['url']}",
                "height": "{$params['companyLogo']['height']}",
                "width": "{$params['companyLogo']['width']}"
            },
            "url": "{$params['companyDomain']}",
            "sameAs": {$socialMediaUrlsJsonStr},
            "address": {
                "@type": "PostalAddress",
		        "streetAddress": "{$params['companyAddress']['streetAddress']}",
		        "addressRegion": "{$params['companyAddress']['state']}",
		        "postalCode": "{$params['companyAddress']['postcode']}",
		        "addressCountry": "{$params['companyAddress']['country']}"
            }
        }
    },
    "publisher": {
        "@type": "Corporation",
        "name": "{$params['companyName']}",
        "url": "{$params['companyDomain']}",
        "sameAs": {$socialMediaUrlsJsonStr},
        "image": {
            "@type": "ImageObject",
            "url": "{$params['socialMediaPreviewImage']['url']}",
            "height": "{$params['socialMediaPreviewImage']['height']}",
            "width": "{$params['socialMediaPreviewImage']['width']}"
        },
        "telephone": "{$params['companyPhone']}",
        "email": "{$params['companyEmail']}",
        "address": {
            "@type": "PostalAddress",
	        "streetAddress": "{$params['companyAddress']['streetAddress']}",
	        "addressRegion": "{$params['companyAddress']['state']}",
	        "postalCode": "{$params['companyAddress']['postcode']}",
	        "addressCountry": "{$params['companyAddress']['country']}"
        },
        "logo": {
            "@type": "ImageObject",
            "url": "{$params['companyLogo']['url']}",
            "height": "{$params['companyLogo']['height']}",
            "width": "{$params['companyLogo']['width']}"
        },
        "location": {
            "@type": "Place",
            "name": "{$params['companyName']}",
            "telephone": "{$params['companyPhone']}",
            "image": {
                "@type": "ImageObject",
                "url": "{$params['socialMediaPreviewImage']['url']}",
                "height": "{$params['socialMediaPreviewImage']['height']}",
                "width": "{$params['socialMediaPreviewImage']['width']}"
            },
            "logo": {
                "@type": "ImageObject",
                "url": "{$params['companyLogo']['url']}",
                "height": "{$params['companyLogo']['height']}",
                "width": "{$params['companyLogo']['width']}"
            },
            "url": "{$params['companyDomain']}",
            "sameAs": {$socialMediaUrlsJsonStr},
            "address": {
                "@type": "PostalAddress",
		        "streetAddress": "{$params['companyAddress']['streetAddress']}",
		        "addressRegion": "{$params['companyAddress']['state']}",
		        "postalCode": "{$params['companyAddress']['postcode']}",
		        "addressCountry": "{$params['companyAddress']['country']}"
            }
        }
    },
    "copyrightHolder": {
        "@type": "Corporation",
        "name": "{$params['companyName']}",
        "url": "{$params['companyDomain']}",
        "sameAs": {$socialMediaUrlsJsonStr},
        "image": {
            "@type": "ImageObject",
            "url": "{$params['socialMediaPreviewImage']['url']}",
            "height": "{$params['socialMediaPreviewImage']['height']}",
            "width": "{$params['socialMediaPreviewImage']['width']}"
        },
        "telephone": "{$params['companyPhone']}",
        "email": "{$params['companyEmail']}",
        "address": {
            "@type": "PostalAddress",
	        "streetAddress": "{$params['companyAddress']['streetAddress']}",
	        "addressRegion": "{$params['companyAddress']['state']}",
	        "postalCode": "{$params['companyAddress']['postcode']}",
	        "addressCountry": "{$params['companyAddress']['country']}"
        },
        "logo": {
            "@type": "ImageObject",
            "url": "{$params['companyLogo']['url']}",
            "height": "{$params['companyLogo']['height']}",
            "width": "{$params['companyLogo']['width']}"
        },
        "location": {
            "@type": "Place",
            "name": "{$params['companyName']}",
            "telephone": "{$params['companyPhone']}",
            "image": {
                "@type": "ImageObject",
                "url": "{$params['socialMediaPreviewImage']['url']}",
                "height": "{$params['socialMediaPreviewImage']['height']}",
                "width": "{$params['socialMediaPreviewImage']['width']}"
            },
            "logo": {
                "@type": "ImageObject",
                "url": "{$params['companyLogo']['url']}",
                "height": "{$params['companyLogo']['height']}",
                "width": "{$params['companyLogo']['width']}"
            },
            "url": "{$params['companyDomain']}",
            "sameAs": {$socialMediaUrlsJsonStr},
            "address": {
                "@type": "PostalAddress",
		        "streetAddress": "{$params['companyAddress']['streetAddress']}",
		        "addressRegion": "{$params['companyAddress']['state']}",
		        "postalCode": "{$params['companyAddress']['postcode']}",
		        "addressCountry": "{$params['companyAddress']['country']}"
            }
        }
    },
    "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": {$breadcrumbList}
    }
}
</script>

<!-- Breadcrumbs -->

<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": {$breadcrumbList}
}
</script>

TEMP;

	}

	public function getSEOMetaTags(){
		return $this->template;
	}

}