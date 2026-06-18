import type { JSX } from "react/jsx-runtime";
import "./Categorie.scss";
import { useFetch } from "../../utilities/useFetch";
import { Earth, Network, LockIcon } from "lucide-react";

export function Categories(): JSX.Element {
  const { data: categories, loading } = useFetch("categorie");

  const catLogo = [
    <Earth color="black" />,
    <Network color="black" />,
    <LockIcon color="black" />,
  ];

  if (loading) return <p>Chargement...</p>;

  return (
    <section className="categories">
      <div className="categories-top">
        <div className="badge-section">EXPLORER PAR DOMAINE</div>
        <h2>Categories</h2>
      </div>
      <div className="categories-container">
        {categories.map((cat, index) => (
          <div key={cat.id} className="categories-container-wrapper">
            {catLogo[index % catLogo.length]}
            {cat.name}
          </div>
        ))}
      </div>
    </section>
  );
}
