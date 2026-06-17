import { BrowserRouter, Routes, Route } from "react-router";
import { Home } from "../pages/Home";
import { Module } from "../pages/Module";
import { Leaderboard } from "../pages/Classement";
import { Commu } from "../pages/Communaute";
import { Certification } from "../pages/Certification";
import type { JSX } from "react/jsx-runtime";

export function Router(): JSX.Element {
  return (
    <>
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Home />}></Route>
          <Route path="/modules" element={<Module />}></Route>
          <Route path="/classement" element={<Leaderboard />}></Route>
          <Route path="/certifications" element={<Certification />}></Route>
          <Route path="/communaute" element={<Commu />}></Route>
        </Routes>
      </BrowserRouter>
    </>
  );
}
