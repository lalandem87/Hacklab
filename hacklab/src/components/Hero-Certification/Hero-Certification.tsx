import type { JSX } from "react/jsx-runtime";
import "./Hero-Certification.scss";
import { useFetch } from "../../utilities/useFetch";
import { ArrowRight, Award, Compass, Tornado, Users } from "lucide-react";

export function HeroCertification(): JSX.Element {
  const { data: certifs, loading: loadingCertifs } = useFetch("certification");
  const { data: usersCertifs, loading: loadingUsersCertifs } =
    useFetch("user/certification");

  if (loadingCertifs || loadingUsersCertifs) return <p>Chargement...</p>;

  return (
    <>
      <div className="hero-certif">
        <div className="badge-section">CERTIFICATIONS OFFICIELLES</div>
        <h1>
          Prouve tes compétences. <em>Décroche ta certification.</em>
        </h1>
        <p>
          Hacklab propose des parcours de certification structurés, reconnus
          dans la communauté hacking. Chaque certification valide un ensemble de
          compétences concrètes obtenues à travers des modules et des challenges
          réels.
        </p>
        <div className="hero-certif-infos">
          <div className="container-info">
            <div className="left">
              <Award />
            </div>
            <div className="right">
              <span className="number-certif">{certifs.length}</span>
              <p>Certifications disponibles</p>
            </div>
          </div>
          <div className="container-info">
            <div className="left">
              <Users />
            </div>
            <div className="right">
              <span className="number-certif">{usersCertifs.length}</span>
              <p>Certifiés sur la plateforme</p>
            </div>
          </div>
          <div className="container-info">
            <div className="left">
              <Tornado />
            </div>
            <div className="right">
              <span className="number-certif">100 % pratique</span>
              <p>Pas de QCM, que du hands-on</p>
            </div>
          </div>
        </div>
        <div className="hero-certif-button">
          <a href="">
            <Compass />
            Explorer les certifications
          </a>
          <a href="">
            Comment ça marche ? <ArrowRight />
          </a>
        </div>
      </div>
    </>
  );
}
