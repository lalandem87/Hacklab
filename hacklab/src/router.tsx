import { Routes, Route } from "react-router";
import { Home } from "./pages/Home";
import type { JSX } from "react/jsx-runtime";
import { Classement } from "./pages/Classement";
import { Module } from "./pages/Module/Module";
import { Certification } from "./pages/Certification";
import { Communaute } from "./pages/Communaute";

import { Register } from "./pages/Register";
import { Login } from "./pages/Login";

export function Router(): JSX.Element {
  return (
    <>
      <Routes>
        <Route path="/" element={<Home />}></Route>
        <Route path="/module" element={<Module />}></Route>
        <Route path="/classement" element={<Classement />}></Route>
        <Route path="/certification" element={<Certification />}></Route>
        <Route path="/communaute" element={<Communaute />}></Route>
        <Route path="/login" element={<Login />}></Route>
        <Route path="/register" element={<Register />}></Route>
      </Routes>
    </>
  );
}
