import type { JSX } from "react/jsx-runtime";
import "./ModuleRoom.scss";
import { NavLink, useParams } from "react-router";
import { useFetchOne } from "../../utilities/useFetchOne";
import {
  ArrowDown,
  ArrowRight,
  ArrowUp,
  Bookmark,
  Check,
  Play,
} from "lucide-react";
import React, { useState } from "react";
import { fetchAPI } from "../../utilities/fetchApi";
import ReactMarkDown from "react-markdown";

export function ModuleRoom(): JSX.Element {
  const { id } = useParams();
  const { data: module, loading } = useFetchOne(`module/${id}`);
  const [isCompleted, setIsCompleted] = useState(false);
  const [openTask, setOpenTask] = useState<number | null>(null);
  const [challengeOpen, setChallengeOpen] = useState(false);
  const [flag, setFlag] = useState("");
  const [result, setResult] = useState<any>(null);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const token =
      localStorage.getItem("token") || sessionStorage.getItem("token");
    if (token) {
      fetchAPI(
        `module/${id}/submit`,
        "POST",
        { submittedFlag: flag },
        token,
      ).then((r) => setResult(r));
    }
  };

  if (loading) return <p>Chargement...</p>;
  return (
    <section className="module-room">
      <div className="module-room-hero">
        <div className="module-room-hero-left">
          <div className="path"></div>
          <h1>{module.name}</h1>
          <p>{module.description}</p>
          <em>{module.point}</em>
        </div>
        <div className="module-room-hero-right">
          <NavLink to={`/module/${id}`}>
            <Play /> Reprende
          </NavLink>
          <button>
            <Bookmark /> Sauvegarder
          </button>
        </div>
      </div>
      <div className="module-container">
        <div className="module-container-course">
          <div className="module-container-course-top">
            <span>Cours</span>
            <h2>{module.course.name}</h2>
          </div>
          <div className="module-container-course-tasks">
            {module.course.task.map((tsk: any, index: number) => {
              return (
                <div key={tsk.id} className="dropdown">
                  <div className="dropdown-intro">
                    <div className="dropdown-intro-left">
                      <div
                        className="checked"
                        style={{
                          backgroundColor: isCompleted ? "#0D2A1F" : "#1A2330",
                          color: isCompleted ? "#00FF88" : "#5A7A8A",
                        }}
                      >
                        <Check />
                      </div>
                      <em>{`Tache ${index + 1}`}</em>
                      <p>{tsk.name}</p>
                    </div>
                    <div className="dropdown-intro-right">
                      <em
                        style={{
                          backgroundColor: isCompleted ? "#0D2A1F" : "#1A2330",
                          color: isCompleted ? "#00FF88" : "#5A7A8A",
                        }}
                        className="completed"
                      >
                        Complété
                      </em>
                      <div
                        className="dropdown-arrow"
                        onClick={() =>
                          setOpenTask(openTask === tsk.id ? null : tsk.id)
                        }
                      >
                        {openTask === tsk.id ? <ArrowUp /> : <ArrowDown />}
                      </div>
                    </div>
                  </div>
                  {openTask === tsk.id && (
                    <div className="dropdown-content">
                      <div className="markdown">
                        <ReactMarkDown>{tsk.content}</ReactMarkDown>
                      </div>
                      <div className="dropdown-content-bottom">
                        <p>Lis attentivement avant de valider.</p>
                        <button onClick={() => setIsCompleted(!isCompleted)}>
                          Valider la tâche <ArrowRight />
                        </button>
                      </div>
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>
        <div className="module-container-challenge">
          <div className="module-container-challenge-top">
            <span>Challenge CTF</span>
            <h2>Mise en pratique</h2>
            <div className="module-container-challenge-task">
              <div className="dropdown-challenge">
                <div className="dropdown-challenge-intro">
                  <div className="dropdown-challenge-intro-left">
                    <div className="dropdown-challenge-intro-left-title">
                      Challenge
                    </div>
                    <em>{module.challenge.name}</em>
                  </div>
                  <div className="dropdown-challenge-intro-right">
                    <em>{module.challenge.point} points</em>
                    <div
                      className="dropdown-arrow"
                      onClick={() => setChallengeOpen(!challengeOpen)}
                    >
                      {challengeOpen ? <ArrowUp /> : <ArrowDown />}
                    </div>
                  </div>
                </div>
                {challengeOpen && (
                  <div className="dropdown-challenge-content">
                    <ReactMarkDown>{module.challenge.content}</ReactMarkDown>
                    <div className="dropdown-challenge-content-bottom">
                      <form action="" method="post" onSubmit={handleSubmit}>
                        <input
                          type="text"
                          name="flag"
                          id="flag"
                          placeholder="FLAG{...}"
                          onChange={(e) => setFlag(e.target.value)}
                        />
                        <button type="submit">
                          Valider la tâche <ArrowRight />
                        </button>
                      </form>
                      {result && <p>{result.message}</p>}
                    </div>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
