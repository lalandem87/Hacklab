import type { JSX } from "react/jsx-runtime";
import "./FormLogin.scss";
import { NavLink } from "react-router";
import React, { useState } from "react";
import { fetchAPI } from "../../utilities/fetchApi";

export function FormLogin(): JSX.Element {
  const [formData, setFormData] = useState({
    email: "",
    password: "",
  });

  const [remember, setRemember] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    fetchAPI("auth/login", "POST", formData).then((r) => {
      if (remember) {
        localStorage.setItem("token", r.token);
      } else {
        sessionStorage.setItem("token", r.token);
      }
    });
  };
  return (
    <section className="login">
      <div className="login-top">
        <h1>Connexion</h1>
        <p>Bon retour parmi nous. Reprends là où tu t'étais arrété.</p>
      </div>
      <form action="" method="post" onSubmit={handleSubmit}>
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
        <div className="wrapper">
          <div className="wrapper-left">
            <input
              type="checkbox"
              name="rememberme"
              id="rememberme"
              onChange={(e) => setRemember(e.target.checked)}
            />
            <label htmlFor="rememberme">Se souvenir de moi</label>
          </div>
          <NavLink to="/resetpasswd">Mot de passe oublié</NavLink>
        </div>
        <button type="submit">Se connecter</button>
      </form>
      <div className="signup">
        Pas encore de compte ?
        <NavLink to="/register">S'inscrire gratuitement</NavLink>
      </div>
    </section>
  );
}
