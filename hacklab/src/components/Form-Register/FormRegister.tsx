import type { JSX } from "react/jsx-runtime";
import "./FormRegister.scss";
import React, { useState } from "react";
import { fetchAPI } from "../../utilities/fetchApi";
import { NavLink } from "react-router";

export function FormRegister(): JSX.Element {
  const [formData, setFormData] = useState({
    gamertag: "",
    email: "",
    password: "",
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    fetchAPI("auth/register", "POST", formData)
      .then((r) => console.log(r))
      .catch((e) => console.error(e));
  };
  return (
    <section className="register">
      <div className="register-container">
        <div className="register-top">
          <h1>Créer un compte</h1>
          <p className="desc-section">
            Rejoins la communauté. Commence à hacker (légalement).
          </p>
        </div>
        <form action="" method="post" onSubmit={handleSubmit}>
          <div className="row">
            <label htmlFor="gamertag">Gamertag</label>
            <input
              type="text"
              name="gamertag"
              id="gamertag"
              onChange={(e) =>
                setFormData({ ...formData, gamertag: e.target.value })
              }
            />
          </div>
          <div className="row">
            <label htmlFor="email">Email</label>
            <input
              type="email"
              name="email"
              id="email"
              onChange={(e) =>
                setFormData({ ...formData, email: e.target.value })
              }
            />
          </div>
          <div className="row">
            <label htmlFor="password">Mot de passe</label>
            <input
              type="password"
              name="password"
              id="password"
              onChange={(e) =>
                setFormData({ ...formData, password: e.target.value })
              }
            />
          </div>
          <button type="submit">Créer mon compte</button>
        </form>
        <div className="alrd-accnt">
          Déjà un compte ? <NavLink to="/login">Se connecter</NavLink>
        </div>
      </div>
    </section>
  );
}
