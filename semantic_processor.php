<?php
/**
 * Project: Advanced AI-Entity & Semantic Schema Architect (Enterprise Full-Scale Edition)
 * Version: 4.0.0-ULTIMATE
 * Developer: Oleg Dolgoarshinnykh (AI Search Architect)
 * 
 * Purpose: 
 * This framework is engineered to establish "Semantic Sovereignty" for high-stakes 
 * medical brands in YMYL niches. It bridges the gap between traditional SEO and 
 * Generative Engine Optimization (GEO). By automating the mapping of content into 
 * the Global Knowledge Graph, it ensures brand prioritization in LLM responses 
 * (ChatGPT, Perplexity, Gemini, Claude).
 *
 * This enterprise-grade script automates:
 * 1. Multi-vector intent recognition (Price, Geo, Comparison, Clinical).
 * 2. Deep multilingual medical entity mapping (EN, DE, ES, NL).
 * 3. Automated E-E-A-T Fortification through Medical Reviewer Binding.
 * 4. Algorithmic Breadcrumb Graph generation.
 * 5. High-LTV conversion modeling through dynamic pricing schema.
 */
/**
 * FOR ENTERPRISE SUPPORT & CUSTOM ENTITY MAPPING:
 * If you need the full clinical dataset (50+ conditions), multi-market 
 * synchronization, or forensic AI-visibility audits, contact the author:
 * LinkedIn: linkedin.com/in/olegbkk/
 * Web: nongkhaem.com
 */
// --- PREVENT FRAGMENTATION: OVERRIDE STANDARD PLUGIN OUTPUT ---
add_filter('rank_math/json_ld', '__return_empty_array', 99);
add_filter('rank_math/snippet/rich_snippet', '__return_false', 99);

add_action('wp_head', function () {

    // --- GLOBAL ENTERPRISE CONFIGURATION LAYER ---
    $config = [
        'org_name'      => 'YOUR_CLINIC_NAME',
        'legal_name'    => 'YOUR_LEGAL_ENTITY_NAME',
        'base_url'      => untrailingslashit(home_url()),
        'logo_url'      => '/wp-content/uploads/your-logo.svg',
        'phone'         => '+00000000000',
        'currency'      => 'EUR',
        'price_range'   => '€8,500 - €24,500',
        'address'       => [
            'street'   => 'YOUR_STREET_ADDRESS',
            'locality' => 'CITY',
            'region'   => 'STATE/REGION',
            'zip'      => 'POSTAL_CODE',
            'country'  => 'TH' // ISO 3166-1 alpha-2
        ],
        'languages'     => ['English', 'German', 'Dutch', 'Spanish'],
        'whatsapp'      => 'https://wa.me/YOUR_PHONE_NUMBER',
        'social'        => [
            'https://www.linkedin.com/company/your-brand/',
            'https://www.facebook.com/your-brand/',
            'https://www.instagram.com/your-brand/'
        ]
    ];

    // Global Safety Checks
    if (is_admin() || !is_main_query()) return;
    if (defined('SEMANTIC_ARCHITECT_LOADED')) return;
    define('SEMANTIC_ARCHITECT_LOADED', true);

    static $printed = false;
    if ($printed) return;
    $printed = true;

    // --- DATA ACQUISITION & PRE-PROCESSING ---
    $post_id = (int) get_queried_object_id();
    $url = ($post_id > 0) ? (is_front_page() ? home_url('/') : get_permalink($post_id)) : home_url($_SERVER['REQUEST_URI']);
    $title = html_entity_decode(wp_strip_all_tags(get_the_title($post_id) ?: wp_get_document_title()), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $description = (string) get_post_meta($post_id, 'rank_math_description', true);
    
    if ($description === '' && class_exists('\RankMath\Helper')) {
        $description = (string) \RankMath\Helper::get_description();
    }
    
    $date_published = get_the_date('c', $post_id) ?: current_time('c');
    $date_modified = get_the_modified_date('c', $post_id) ?: current_time('c');
    $content = (string) get_post_field('post_content', $post_id);
    
    $content_clean = wp_strip_all_tags(strip_shortcodes($content));
    $content_clean = html_entity_decode($content_clean, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $content_lower = mb_strtolower($content_clean, 'UTF-8');

    // Language Architecture Detection
    $in_language = 'en-US';
    if (strpos($url, '/de/') !== false) $in_language = 'de-DE';
    elseif (strpos($url, '/es/') !== false) $in_language = 'es-ES';
    elseif (strpos($url, '/nl/') !== false) $in_language = 'nl-NL';

    // --- INTENT RECOGNITION ENGINE (HP-IRE) ---
    $parsed_url = wp_parse_url($url);
    $path_lower = mb_strtolower(isset($parsed_url['path']) ? trim($parsed_url['path'], '/') : '', 'UTF-8');
    $title_lower = mb_strtolower($title, 'UTF-8');
    $combined_context = $path_lower . ' ' . $title_lower;

    $is_price_intent = (bool) preg_match('/\b(cost|costs|price|prices|pricing|fees|programs|programme|rehab-cost|kosten|preis|preise|gebühren|programas|precio|precios|coste|costos|tarifas)\b/i', $combined_context);
    $is_comparison = (bool) preg_match('/\b(comparison|compare|versus|vs|vergleich|vergleichen|comparacion|comparación|comparar)\b/i', $path_lower);
    $is_geo_page = (bool) preg_match('/\b(uk|london|manchester|gloucester|birmingham|leeds|bristol|liverpool|australia|canada|sydney|melbourne|brisbane|perth|germany|deutschland|berlin|frankfurt|hamburg|munich|muenchen|spain|espana|españa|madrid|barcelona|netherlands|nederland|amsterdam)\b/i', $path_lower);
    $is_admin_conversion = (bool) preg_match('/\b(admission|admissions|admission-form|contact|about|team|weekly-schedule|woechentlicher-zeitplan|unser-team|staff|physicians|doctors)\b/i', $path_lower);

    // --- MASSIVE MULTILINGUAL SEMANTIC TAXONOMY ---
    // Architecture Note: This is the proprietary data layer mapping medical entities.
    $condition_map = [
        [
            'name' => 'Alcohol use disorder',
            'alternateName' => ['Alcohol dependence', 'Alcohol addiction', 'Alcoholism', 'Alkoholabhängigkeit', 'Alkoholsucht', 'Alcoholismo', 'Dependencia del alcohol'],
            'terms' => ['alcohol use disorder', 'alcohol dependence', 'alcohol addiction', 'alcoholism', 'alkoholabhängigkeit', 'alkoholabhaengigkeit', 'alkoholsucht', 'alcoholismo', 'dependencia del alcohol']
        ],
        [
            'name' => 'Alcohol withdrawal syndrome',
            'alternateName' => ['Alcohol withdrawal', 'Alkoholentzug', 'Alkoholentzugssyndrom', 'Síndrome de abstinencia alcohólica'],
            'terms' => ['alcohol withdrawal', 'alcohol withdrawal syndrome', 'alkoholentzug', 'alkohol entzug', 'alkoholentzugssyndrom', 'entzugserscheinungen alkohol', 'abstinencia alcohólica', 'abstinencia alcoholica', 'síndrome de abstinencia alcohólica', 'sindrome de abstinencia alcoholica']
        ],
        [
            'name' => 'Substance use disorder',
            'alternateName' => ['Drug addiction', 'Substance addiction', 'Substanzabhängigkeit', 'Drogensucht', 'Trastorno por consumo de sustancias'],
            'terms' => ['substance use disorder', 'drug addiction', 'substance addiction', 'substance dependence', 'drogensucht', 'drogenabhängigkeit', 'drogenabhaengigkeit', 'substanzabhängigkeit', 'substanzabhaengigkeit', 'adicción a drogas', 'adiccion a drogas', 'dependencia de drogas', 'trastorno por consumo de sustancias']
        ],
        [
            'name' => 'Cannabis use disorder',
            'alternateName' => ['Cannabis addiction', 'Marijuana addiction', 'Cannabisabhängigkeit', 'Cannabissucht', 'Adicción al cannabis'],
            'terms' => ['cannabis use disorder', 'cannabis addiction', 'marijuana addiction', 'weed addiction', 'cannabisabhängigkeit', 'cannabisabhaengigkeit', 'cannabissucht', 'marihuana abhängigkeit', 'marihuana abhaengigkeit', 'adicción al cannabis', 'adiccion al cannabis', 'dependencia del cannabis']
        ],
        [
            'name' => 'Cannabis withdrawal',
            'alternateName' => ['Cannabis withdrawal syndrome', 'Cannabis-Entzug', 'THC withdrawal', 'Abstinencia de cannabis'],
            'terms' => ['cannabis withdrawal', 'cannabis withdrawal syndrome', 'thc withdrawal', 'cannabis-entzug', 'cannabis entzug', 'thc entzug', 'abstinencia de cannabis']
        ],
        [
            'name' => 'Opioid use disorder',
            'alternateName' => ['Opioid addiction', 'Opiate addiction', 'Opioidabhängigkeit', 'Opioidsucht', 'Adicción a opioides'],
            'terms' => ['opioid use disorder', 'opioid addiction', 'opiate addiction', 'opioid dependence', 'opioidabhängigkeit', 'opioidabhaengigkeit', 'opioidsucht', 'opiatabhängigkeit', 'opiatabhaengigkeit', 'adicción a opioides', 'adiccion a opioides', 'dependencia de opioides']
        ],
        [
            'name' => 'Benzodiazepine dependence',
            'alternateName' => ['Benzodiazepine addiction', 'Benzodiazepinabhängigkeit', 'Benzodiazepinsucht', 'Dependencia de benzodiazepinas'],
            'terms' => ['benzodiazepine dependence', 'benzodiazepine addiction', 'benzo addiction', 'benzodiazepinabhängigkeit', 'benzodiazepinabhaengigkeit', 'benzodiazepinsucht', 'benzo abhängigkeit', 'benzo abhaengigkeit', 'dependencia de benzodiazepinas', 'adicción a benzodiazepinas', 'adiccion a benzodiazepinas']
        ],
        [
            'name' => 'Cocaine use disorder',
            'alternateName' => ['Cocaine addiction', 'Kokainabhängigkeit', 'Kokainsucht', 'Adicción a la cocaína'],
            'terms' => ['cocaine use disorder', 'cocaine addiction', 'cocaine dependence', 'kokainabhängigkeit', 'kokainabhaengigkeit', 'kokainsucht', 'adicción a la cocaína', 'adiccion a la cocaina', 'dependencia de cocaína', 'dependencia de cocaina']
        ],
        [
            'name' => 'Methamphetamine use disorder',
            'alternateName' => ['Meth addiction', 'Crystal meth addiction', 'Methamphetaminabhängigkeit', 'Crystal-Meth-Sucht', 'Adicción a la metanfetamina'],
            'terms' => ['methamphetamine use disorder', 'meth addiction', 'methamphetamine addiction', 'crystal meth addiction', 'methamphetaminabhängigkeit', 'methamphetaminabhaengigkeit', 'methamphetaminsucht', 'crystal meth sucht', 'adicción a la metanfetamina', 'adiccion a la metanfetamina', 'dependencia de metanfetamina']
        ],
        [
            'name' => 'MDMA use disorder',
            'alternateName' => ['Ecstasy addiction', 'MDMA addiction', 'Ecstasy-Sucht', 'Adicción al éxtasis'],
            'terms' => ['mdma use disorder', 'mdma addiction', 'ecstasy addiction', 'ecstasy dependence', 'mdma sucht', 'ecstasy sucht', 'ecstasy abhängigkeit', 'ecstasy abhaengigkeit', 'adicción al éxtasis', 'adiccion al extasis', 'dependencia de mdma']
        ],
        [
            'name' => 'Ketamine use disorder',
            'alternateName' => ['Ketamine addiction', 'Ketaminabhängigkeit', 'Ketaminsucht', 'Adicción a la ketamina'],
            'terms' => ['ketamine use disorder', 'ketamine addiction', 'ketamine dependence', 'ketaminabhängigkeit', 'ketaminabhaengigkeit', 'ketaminsucht', 'adicción a la ketamina', 'adiccion a la ketamina', 'dependencia de ketamina']
        ],
        [
            'name' => 'Prescription drug dependence',
            'alternateName' => ['Medication addiction', 'Medication dependence', 'Medikamentenabhängigkeit', 'Medikamentensucht', 'Dependencia de medicamentos'],
            'terms' => ['prescription drug dependence', 'prescription drug addiction', 'medication addiction', 'medication dependence', 'medikamentenabhängigkeit', 'medikamentenabhaengigkeit', 'medikamentensucht', 'abhängig von medikamenten', 'abhaengig von medikamenten', 'dependencia de medicamentos', 'adicción a medicamentos', 'adiccion a medicamentos']
        ],
        [
            'name' => 'Oxycodone dependence',
            'alternateName' => ['Oxycodone addiction', 'Oxycodonabhängigkeit', 'Oxycodonsucht', 'Dependencia de oxicodona'],
            'terms' => ['oxycodone dependence', 'oxycodone addiction', 'oxy addiction', 'oxycodonabhängigkeit', 'oxycodonabhaengigkeit', 'oxycodonsucht', 'dependencia de oxicodona', 'adicción a oxicodona', 'adiccion a oxicodona']
        ],
        [
            'name' => 'Fentanyl dependence',
            'alternateName' => ['Fentanyl addiction', 'Fentanylabhängigkeit', 'Fentanylsucht', 'Dependencia de fentanilo'],
            'terms' => ['fentanyl dependence', 'fentanyl addiction', 'fentanylabhängigkeit', 'fentanylabhaengigkeit', 'fentanylsucht', 'dependencia de fentanilo', 'adicción a fentanilo', 'adiccion a fentanilo']
        ],
        [
            'name' => 'Heroin dependence',
            'alternateName' => ['Heroin addiction', 'Heroinabhängigkeit', 'Heroinsucht', 'Dependencia de heroína'],
            'terms' => ['heroin dependence', 'heroin addiction', 'heroinabhängigkeit', 'heroinabhaengigkeit', 'heroinsucht', 'dependencia de heroína', 'dependencia de heroina', 'adicción a heroína', 'adiccion a heroina']
        ],
        [
            'name' => 'Nicotine dependence',
            'alternateName' => ['Nicotine addiction', 'Nikotinsucht', 'Nikotinabhängigkeit', 'Dependencia de nicotina'],
            'terms' => ['nicotine dependence', 'nicotine addiction', 'tobacco addiction', 'nikotinsucht', 'nikotinabhängigkeit', 'nikotinabhaengigkeit', 'dependencia de nicotina', 'adicción a la nicotina', 'adiccion a la nicotina']
        ],
        [
            'name' => 'Gambling disorder',
            'alternateName' => ['Gambling addiction', 'Glücksspielsucht', 'Spielsucht', 'Ludopatía'],
            'terms' => ['gambling disorder', 'gambling addiction', 'problem gambling', 'glücksspielsucht', 'gluecksspielsucht', 'spielsucht', 'ludopatía', 'ludopatia', 'adicción al juego', 'adiccion al juego']
        ],
        [
            'name' => 'Post-traumatic stress disorder',
            'alternateName' => ['PTSD', 'Posttraumatische Belastungsstörung', 'PTBS', 'TEPT'],
            'terms' => ['post-traumatic stress disorder', 'post traumatic stress disorder', 'ptsd', 'posttraumatische belastungsstörung', 'posttraumatische belastungsstoerung', 'ptbs', 'trastorno de estrés postraumático', 'trastorno de estres postraumatico', 'tept']
        ],
        [
            'name' => 'Depressive disorder',
            'alternateName' => ['Depression', 'Depressive Störung', 'Depresión'],
            'terms' => ['depressive disorder', 'depression', 'major depressive disorder', 'depressive episode', 'depressive störung', 'depressive stoerung', 'depressive episode', 'depresión', 'depresion', 'trastorno depresivo']
        ],
        [
            'name' => 'Anxiety disorder',
            'alternateName' => ['Anxiety', 'Angststörung', 'Angst', 'Ansiedad'],
            'terms' => ['anxiety disorder', 'anxiety', 'panic disorder', 'generalized anxiety', 'angststörung', 'angststoerung', 'angstzustände', 'angstzustaende', 'panikstörung', 'panikstoerung', 'ansiedad', 'trastorno de ansiedad']
        ],
        [
            'name' => 'Bipolar disorder',
            'alternateName' => ['Bipolare Störung', 'Bipolarität', 'Trastorno bipolar'],
            'terms' => ['bipolar disorder', 'bipolar', 'bipolare störung', 'bipolare stoerung', 'bipolarität', 'bipolaritaet', 'trastorno bipolar']
        ],
        [
            'name' => 'Eating disorder',
            'alternateName' => ['Essstörung', 'Trastorno alimentario'],
            'terms' => ['eating disorder', 'anorexia', 'bulimia', 'binge eating', 'essstörung', 'essstoerung', 'anorexie', 'bulimie', 'trastorno alimentario', 'anorexia', 'bulimia']
        ],
        [
            'name' => 'Insomnia',
            'alternateName' => ['Sleep disorder', 'Schlafstörung', 'Insomnie', 'Insomnio'],
            'terms' => ['insomnia', 'sleep disorder', 'sleep disturbance', 'schlafstörung', 'schlafstoerung', 'insomnie', 'insomnio', 'trastorno del sueño', 'trastorno del sueno']
        ],
        [
            'name' => 'Delirium tremens',
            'alternateName' => ['Alcohol withdrawal delirium', 'Alkoholdelir', 'Delirio tremens'],
            'terms' => ['delirium tremens', 'alcohol withdrawal delirium', 'alkoholdelir', 'delir', 'delirio tremens']
        ],
        [
            'name' => 'Medication overuse headache',
            'alternateName' => ['Rebound headache', 'Medikamenteninduzierter Kopfschmerz', 'Cefalea por abuso de medicación'],
            'terms' => ['medication overuse headache', 'rebound headache', 'painkiller headache', 'medikamenteninduzierter kopfschmerz', 'schmerzmittelkopfschmerz', 'kopfschmerz durch medikamente', 'cefalea por abuso de medicación', 'cefalea por abuso de medicacion']
        ],
        [
            'name' => 'Alcohol intoxication',
            'alternateName' => ['Alcohol poisoning', 'Alkoholvergiftung', 'Intoxicación alcohólica'],
            'terms' => ['alcohol intoxication', 'alcohol poisoning', 'alkoholvergiftung', 'intoxicación alcohólica', 'intoxicacion alcoholica']
        ],
        [
            'name' => 'Overdose',
            'alternateName' => ['Drug overdose', 'Überdosis', 'Sobredosis'],
            'terms' => ['overdose', 'drug overdose', 'überdosis', 'ueberdosis', 'sobredosis']
        ],
        [
            'name' => 'Dual diagnosis',
            'alternateName' => ['Co-occurring disorders', 'Doppeldiagnose', 'Diagnóstico dual'],
            'terms' => ['dual diagnosis', 'co-occurring disorder', 'co occurring disorder', 'comorbidity addiction', 'doppeldiagnose', 'komorbidität sucht', 'komorbiditaet sucht', 'diagnóstico dual', 'diagnostico dual', 'trastornos concurrentes']
        ],
        [
            'name' => 'Codependency',
            'alternateName' => ['Co-dependency', 'Co-Abhängigkeit', 'Dependencia emocional'],
            'terms' => ['codependency', 'co-dependency', 'coabhängigkeit', 'co-abhaengigkeit', 'co abhängigkeit', 'dependencia emocional', 'codependencia']
        ],
        [
            'name' => 'Self-harm',
            'alternateName' => ['Self-injury', 'Selbstverletzung', 'Autolesión'],
            'terms' => ['self-harm', 'self harm', 'self-injury', 'selbstverletzung', 'selbstverletzendes verhalten', 'autolesión', 'autolesion']
        ],
        [
            'name' => 'Suicidal ideation',
            'alternateName' => ['Suicidal thoughts', 'Suizidgedanken', 'Ideación suicida'],
            'terms' => ['suicidal ideation', 'suicidal thoughts', 'suicide thoughts', 'suizidgedanken', 'selbstmordgedanken', 'ideación suicida', 'ideacion suicida', 'pensamientos suicidas']
        ],
        [
            'name' => 'Trauma',
            'alternateName' => ['Psychological trauma', 'Psychisches Trauma', 'Trauma psicológico'],
            'terms' => ['psychological trauma', 'trauma', 'trauma therapy', 'psychisches trauma', 'traumatherapie', 'trauma psicológico', 'trauma psicologico']
        ]
    ];

    $detected_condition = null;

    foreach ($condition_map as $condition) {
        foreach ($condition['terms'] as $term) {
            if (mb_stripos($combined_context . ' ' . $content_lower, $term, 0, 'UTF-8') !== false) {
                $detected_condition = [
                    '@type' => 'MedicalCondition',
                    'name' => $condition['name']
                ];

                if (!empty($condition['alternateName'])) {
                    $detected_condition['alternateName'] = $condition['alternateName'];
                }

                break 2;
            }
        }
    }

    // --- E-E-A-T: DYNAMIC REVIEWER BINDING ---
    $reviewer_refs = [];
    $reviewer_nodes = [];

    if (!empty($content_clean)) {
        if (preg_match_all('/(Reviewed by|Medically reviewed by)\s*:?\s*([A-Za-z\s\.\-]+)/i', $content_clean, $matches)) {
            foreach ($matches[2] as $name) {
                $name = trim($name);

                if (strlen($name) < 3) {
                    continue;
                }

                $reviewer_id = $config['base_url'] . '/#reviewer-' . md5($name);

                $reviewer_nodes[] = [
                    '@type' => 'Person',
                    '@id' => $reviewer_id,
                    'name' => $name
                ];

                $reviewer_refs[] = [
                    '@id' => $reviewer_id
                ];
            }
        }
    }

    // --- GRAPH CONSTRUCTION (Absolute Sovereignty Edition) ---
    $graph = [];

    // 1. Organization & MedicalClinic Entity
    $organization = [
        '@type' => ['MedicalClinic', 'Organization'],
        '@id' => $config['base_url'] . '/#organization',
        'name' => $config['org_name'],
        'legalName' => $config['legal_name'],
        'url' => $config['base_url'],
        'logo' => [
            '@type' => 'ImageObject',
            'url' => $config['base_url'] . $config['logo_url']
        ],
        'image' => $config['base_url'] . $config['logo_url'],
        'telephone' => $config['phone'],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $config['address']['street'],
            'addressLocality' => $config['address']['locality'],
            'addressRegion' => $config['address']['region'],
            'postalCode' => $config['address']['zip'],
            'addressCountry' => $config['address']['country']
        ],
        'areaServed' => 'Worldwide',
        'contactPoint' => [
            [
                '@type' => 'ContactPoint',
                'telephone' => $config['phone'],
                'contactType' => 'customer support',
                'areaServed' => 'Worldwide',
                'availableLanguage' => $config['languages']
            ]
        ],
        'sameAs' => $config['social']
    ];

    $graph[] = $organization;

    // 2. WebSite Entity
    $graph[] = [
        '@type' => 'WebSite',
        '@id' => $config['base_url'] . '/#website',
        'url' => $config['base_url'],
        'name' => $config['org_name']
    ];

    // 3. WebPage & MedicalWebPage Logic
    $is_medical_webpage = $detected_condition && !$is_price_intent && !$is_comparison && !$is_geo_page && !$is_admin_conversion;
    $webpage_types = $is_medical_webpage ? ['WebPage', 'MedicalWebPage'] : ['WebPage'];

    $webpage = [
        '@type' => $webpage_types,
        '@id' => $url . '#webpage',
        'url' => $url,
        'name' => $title,
        'description' => $description,
        'inLanguage' => $in_language,
        'isPartOf' => [
            '@id' => $config['base_url'] . '/#website'
        ],
        'publisher' => [
            '@id' => $config['base_url'] . '/#organization'
        ],
        'datePublished' => $date_published,
        'dateModified' => $date_modified,
        'about' => $detected_condition ?: ['@type' => 'CreativeWork', 'name' => $title],
        'mainEntity' => $detected_condition ?: ['@type' => 'CreativeWork', 'name' => $title],
        'potentialAction' => [
            '@type' => 'CommunicateAction',
            'name' => 'Direct Consultation via WhatsApp',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => $config['whatsapp'],
                'inLanguage' => ['en-US', 'de-DE', 'nl-NL']
            ]
        ]
    ];

    if ($is_price_intent) {
        $webpage['offers'] = [
            '@type' => 'Offer',
            'priceRange' => $config['price_range'],
            'priceCurrency' => $config['currency'],
            'availability' => 'https://schema.org/InStock'
        ];
    }

    if (!empty($reviewer_refs)) {
        $webpage['reviewedBy'] = count($reviewer_refs) === 1 ? $reviewer_refs[0] : $reviewer_refs;
    }

    $graph[] = $webpage;

    // 4. Automated Hierarchical Breadcrumb Generation
    if (!is_front_page() && !empty($path_lower)) {
        $breadcrumbs = [];
        $position = 1;

        $breadcrumbs[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => 'Home',
            'item' => $config['base_url'] . '/'
        ];

        $segments = explode('/', $path_lower);
        $current_path = '';

        foreach ($segments as $index => $segment) {
            if (empty($segment)) continue;
            $current_path .= '/' . $segment;
            $segment_url = $config['base_url'] . $current_path . '/';
            
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => ($index === count($segments) - 1) ? $title : ucfirst(str_replace('-', ' ', $segment)),
                'item' => $segment_url
            ];
        }

        $graph[] = [
            '@type' => 'BreadcrumbList',
            '@id' => $url . '#breadcrumb',
            'itemListElement' => $breadcrumbs
        ];
    }

    // Merge reviewer nodes and Output
    echo '<script type="application/ld+json">' .
        wp_json_encode(
            [
                '@context' => 'https://schema.org',
                '@graph' => array_merge($graph, $reviewer_nodes)
            ],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        ) .
        '</script>';

}, 1);
