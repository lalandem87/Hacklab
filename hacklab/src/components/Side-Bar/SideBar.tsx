import type { JSX } from "react/jsx-runtime";
import { useFetch } from "../../utilities/useFetch";
import "./SideBar.scss";

interface SideBarProps {
  onCatChange: (cat: string) => void;
  onDiffChange: (cat: string) => void;
}

export function SideBar({
  onCatChange,
  onDiffChange,
}: SideBarProps): JSX.Element {
  const { data: categories, loading } = useFetch("categorie");

  if (loading) return <p>Chargement...</p>;

  return (
    <section className="side-bar">
      <div className="side-bar-wrapper">
        <div className="side-bar-wrapper-title">Catégorie</div>
        <div className="side-bar-wrapper-cats">
          <button onClick={() => onCatChange("Tous")}>Tous</button>
          {categories.map((cat) => {
            return (
              <button key={cat.id} onClick={() => onCatChange(cat.name)}>
                {cat.name}
              </button>
            );
          })}
        </div>
      </div>
      <div className="side-bar-wrapper">
        <div className="side-bar-wrapper-title">Difficulté</div>
        <div className="side-bar-wrapper-dif">
          <button onClick={() => onDiffChange("Tous")}>Tous niveaux</button>
          <button onClick={() => onDiffChange("Facile")}>Débutant</button>
          <button onClick={() => onDiffChange("Intermediaire")}>
            Intermédiaire
          </button>
          <button onClick={() => onDiffChange("Avance")}>Avancé</button>
        </div>
      </div>
    </section>
  );
}
