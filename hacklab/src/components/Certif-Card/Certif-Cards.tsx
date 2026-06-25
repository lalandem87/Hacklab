import type { JSX } from "react/jsx-runtime";
import "./Certif-Cards.scss";
import { useFetch } from "../../utilities/useFetch";
import { ArrowRight } from "lucide-react";

export function CertifCards(): JSX.Element {
  const { data: certifs, loading } = useFetch("certification");
  if (loading) return <p>Chargement...</p>;
  return (
    <>
      <section className="certifs">
        <div className="certifs-top">
          <h2>Toutes les certifications</h2>
          <span>{certifs.length} certifications disponibles</span>
        </div>
        <div className="certifs-container">
          {certifs.map((certif) => {
            return (
              <div key={certif.id} className="certif-card">
                <img
                  src={`http://localhost:8000${certif.image}`}
                  alt="logo certification"
                />
                <h3>{certif.name}</h3>
                <p>{certif.description}</p>
                <button>
                  Explorer <ArrowRight />
                </button>
              </div>
            );
          })}
        </div>
      </section>
    </>
  );
}
