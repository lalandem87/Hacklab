import type { JSX } from "react/jsx-runtime";
import { NavLink } from "react-router";
import { Tornado } from "lucide-react";
import "./Module.scss";
import { useAuth } from "../../context/AuthContext";
import { useFetchWithToken } from "../../utilities/useFetchWithToken";

interface ModuleCardProps {
  modules: any[];
}

export function ModuleCard({ modules }: ModuleCardProps): JSX.Element {
  const { token } = useAuth();
  const { data: me } = useFetchWithToken("user/me", "GET", token);
  const isModuleAlreadyStarted = (mod: any) => {
    return me?.userModules?.find(
      (usrModule: any) => usrModule.module?.id === mod.id,
    );
  };
  return (
    <div className="catalogue-module-container">
      {modules.map((mod) => {
        return (
          <div key={mod.id} className="module-card">
            <div className="module-card-top">
              <div className="module-card-top-left">
                <span className="difficulty">{mod.difficulty}</span>
                <h3 className="title">{mod.name}</h3>
                <em className="cat">{mod.categorie.name}</em>
              </div>
              <div className="module-card-top-right">
                <span className="point">
                  <Tornado size={15} color="black" />
                  {mod.course.point + mod.challenge.point} pts
                </span>
              </div>
            </div>
            <div className="module-card-bottom">
              <p className="desc">{mod.description}</p>
              <NavLink to={`/module/${mod.id}`}>
                {isModuleAlreadyStarted(mod) ? "Continuer" : "Démarrer"}
              </NavLink>
            </div>
          </div>
        );
      })}
    </div>
  );
}
