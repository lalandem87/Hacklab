import type { JSX } from "react/jsx-runtime";
import "./DashboardUser.scss";
import avatar from "../../assets/photo-user.png";
import { useParams } from "react-router";
import {
  ArrowRight,
  Award,
  Calendar,
  Database,
  Earth,
  Flag,
  Flame,
  Play,
  Settings,
  Star,
} from "lucide-react";
import { useFetch } from "../../utilities/useFetch";
import { ProfilItem } from "../Profil-Item/ProfilItem";
import { useAuth } from "../../context/AuthContext";
import { useFetchWithToken } from "../../utilities/useFetchWithToken";

export function DashboardUser(): JSX.Element {
  const { token } = useAuth();
  const { id } = useParams();
  const { data: user, loading } = useFetchWithToken("user/me", "GET", token);
  const { data: userCertifs, loading: loadingUserCertifs } = useFetchWithToken(
    `user/${id}/certification`,
    "GET",
    token,
  );
  const { data: certif, loading: loadindCertifs } = useFetch("certification");
  const { data: userModules, loading: loadindUserModules } = useFetch("module");

  if (loading) return <p>Chargement...</p>;

  return (
    <>
      <section className="dashboard-user">
        <div className="dashboard-user-profile">
          <div className="infos">
            <div className="avatar">
              <img src={avatar} alt="photo avatar" />
            </div>
            <div className="gamertag">{user.gamertag}</div>
            <span>Débutant</span>
          </div>
          <div className="profile-stat">
            <div className="profile-stat-label">
              <Calendar />
              <span>Membre depuis</span>
            </div>
            <div className="profile-stat-value">Jan. 2025</div>
          </div>
          <div className="profile-stat">
            <div className="profile-stat-label">
              <Flame />
              <span>Streak actuel</span>
            </div>
            <div className="profile-stat-value">0 jours</div>
          </div>
          <div className="profile-stat">
            <div className="profile-stat-label">
              <Flag />
              <span>Modules réussis</span>
            </div>

            <div className="profile-stat-value">{user.userModules.length}</div>
          </div>
          <button>
            <Settings /> Paramètre du profil
          </button>
        </div>
        <div className="dashboard-user-content">
          <div className="dashboard-user-content-top">
            <div className="dashboard-user-content-top-left">
              <h1>Mon tableau de bord</h1>
              <p>Bienvenue, {user.gamertag}. Continue sur ta lancée.</p>
            </div>
            <div className="dashboard-user-content-top-right">
              <button>
                <Play /> Continuer l'apprentissage
              </button>
            </div>
          </div>
          <div className="container-card">
            <div className="card">
              <div className="card-logo">
                <Star />
              </div>
              <div className="card-content">
                <div className="pointearn">{user.pointEarn}</div>
                <p>Points totaux</p>
              </div>
            </div>
            <div className="card">
              <div className="card-top">
                <div className="card-logo">
                  <Database />
                </div>
                <p>24 % du catalogue</p>
              </div>
              <div className="card-content">
                <div className="module-completed">
                  {user.userModules.length}
                </div>
                <p>Modules complétés</p>
                <div className="">sur {userModules.length} disponibles</div>
              </div>
            </div>
            <div className="card">
              <div className="card-top">
                <div className="card-logo">
                  <Award />
                </div>
                <p>{certif.length} certifications au total</p>
              </div>
              <div className="card-content">
                <div className="certification-earn">
                  {user.userCertifications.length}
                </div>
                <p>Certifications obtenues</p>
              </div>
            </div>
          </div>
          <div className="user-activities">
            <div className="user-activities-modules">
              <ProfilItem
                classname="recent-modules"
                subclass="modules"
                classname2="module-item"
                title="Modules récents"
                data={user.userModules}
                renderItem={(d) => (
                  <>
                    <span>{d.module?.name}</span>
                    <span>{d.module?.course?.name}</span>
                  </>
                )}
              />
            </div>
            <div className="user-activities-certifs">
              <ProfilItem
                classname="recent-certifs"
                subclass="certifs"
                classname2="certif-item"
                title="Certifications"
                data={user.userCertifications}
                renderItem={(d) => (
                  <>
                    <span>{d.certification?.name}</span>
                  </>
                )}
              />
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
