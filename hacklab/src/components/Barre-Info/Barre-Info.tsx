import type { JSX } from "react/jsx-runtime";
import { useState, useEffect } from "react";
import "./Barre-Info.scss";

interface Stats {
  modules: number;
  users: number;
  categories: number;
}

function useStats() {
  const [stats, setStats] = useState<Stats>({
    modules: 0,
    users: 0,
    categories: 0,
  });

  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchStats = async () => {
      try {
        const [modules, users, categories] = await Promise.all([
          fetch("http://127.0.0.1:8000/api/module").then((r) => r.json()),
          fetch("http://127.0.0.1:8000/api/user").then((r) => r.json()),
          fetch("http://127.0.0.1:8000/api/categorie").then((r) => r.json()),
        ]);

        setStats({
          modules: modules.length,
          users: users.length,
          categories: categories.length,
        });
      } catch (e) {
        console.error(e);
      } finally {
        setLoading(false);
      }
    };
    fetchStats();
  }, []);
  return { stats, loading };
}

export function BarreInfo(): JSX.Element {
  const { stats, loading } = useStats();
  return (
    <section className="barre-info">
      <div className="stat">
        <span className="stat-value">{loading ? "..." : stats.modules}+</span>
        <span className="stat-label">Modules disponibles</span>
      </div>
      <div className="stat">
        <span className="stat-value">{loading ? "..." : stats.users}</span>
        <span className="stat-label">Utilisateur actifs</span>
      </div>
      <div className="stat">
        <span className="stat-value">{loading ? "..." : stats.categories}</span>
        <span className="stat-label">Categories</span>
      </div>
      <div className="stat">
        <span className="stat-value">98%</span>
        <span className="stat-label">Taux de satisfaction</span>
      </div>
    </section>
  );
}
