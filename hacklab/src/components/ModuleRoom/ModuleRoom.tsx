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
import React, { useEffect, useState } from "react";
import { fetchAPI } from "../../utilities/fetchApi";
import ReactMarkDown from "react-markdown";
import { useAuth } from "../../context/AuthContext";
import { useFetchWithToken } from "../../utilities/useFetchWithToken";
import { Toast } from "../Toast/Toast";

export function ModuleRoom(): JSX.Element {
  const { id } = useParams();
  const { data: module, loading } = useFetchOne(`module/${id}`);
  const [completedTasks, setCompletedTasks] = useState<Record<number, boolean>>(
    {},
  );
  const [openTask, setOpenTask] = useState<number | null>(null);
  const [challengeOpen, setChallengeOpen] = useState(false);
  const [flag, setFlag] = useState("");
  const [result, setResult] = useState<any>(null);
  const [answerQuestion, setAnswerQuestion] = useState<Record<number, string>>(
    {},
  );
  const [resultQuestion, setResultQuestion] = useState<Record<number, boolean>>(
    {},
  );

  const [toast, setToast] = useState<{
    message: string;
    type: "success" | "error";
  } | null>(null);

  const { token } = useAuth();
  const { data: me } = useFetchWithToken("user/me", "GET", token);

  useEffect(() => {
    if (!me) return;

    const tasks: Record<number, boolean> = {};
    me.userTasks?.forEach((ut: any) => {
      tasks[ut.task.id] = ut.solved;
    });
    setCompletedTasks(tasks);

    const questions: Record<number, boolean> = {};
    me.userTaskQuestions?.forEach((utq: any) => {
      questions[utq.question.id] = utq.solved;
    });
    setResultQuestion(questions);

    const answers: Record<number, string> = {};
    me.userTaskQuestions?.forEach((utq: any) => {
      answers[utq.question.id] = utq.submittedAnswer;
    });
    setAnswerQuestion(answers);

    const solvedModule = me.userModules?.find(
      (um: any) => um.module?.id === Number(id),
    );
    if (solvedModule) {
      setResult({ message: "Module déjà complété !" });
      setFlag(solvedModule.submittedFlag);
    }
  }, [me]);

  const handleAnswer = (questId: number, answer: string) => {
    setAnswerQuestion({ ...answerQuestion, [questId]: answer });
    fetchAPI(
      `task/questions/${questId}/verify`,
      "POST",
      { answer },
      token || undefined,
    ).then((r) => {
      if (r.correct !== undefined) {
        setResultQuestion({ ...resultQuestion, [questId]: r.correct });
        setToast({
          message: r.correct ? "Bonne réponse !" : "Mauvaise réponse",
          type: r.correct ? "success" : "error",
        });
      }
    });
  };

  const isTaskValid = (tsk: any) => {
    return tsk.taskQuestions.every(
      (quest: any) => resultQuestion[quest.id] === true,
    );
  };

  const handleCompletedTask = (taskId: number, tsk: any) => {
    if (!isTaskValid(tsk)) {
      setToast({
        message: "Réponds à toutes les questions avant de valider",
        type: "error",
      });
      return;
    }
    fetchAPI(`task/${taskId}/verify`, "POST", undefined, token)
      .then((r) => {
        if (r.message?.includes("completed")) {
          setCompletedTasks({ ...completedTasks, [taskId]: true });
        }
        setToast({
          message: r.message?.includes("completed")
            ? "Tâche complété"
            : r.message,
          type: r.message?.includes("completed") ? "success" : "error",
        });
      })
      .catch((e) => console.error(e));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (token) {
      fetchAPI(
        `module/${id}/submit`,
        "POST",
        { submittedFlag: decodeURIComponent(flag) },
        token,
      ).then((r) => {
        setResult(r);
        setToast({
          message: r.message,
          type: r.message?.includes("done") ? "success" : "error",
        });
      });
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
                  <div
                    className="dropdown-intro"
                    onClick={() =>
                      setOpenTask(openTask === tsk.id ? null : tsk.id)
                    }
                  >
                    <div className="dropdown-intro-left">
                      <div
                        className="checked"
                        style={{
                          backgroundColor: completedTasks[tsk.id]
                            ? "#0D2A1F"
                            : "#1A2330",
                          color: completedTasks[tsk.id] ? "#00FF88" : "#5A7A8A",
                        }}
                      >
                        <Check />
                      </div>
                      <em>{`Tache ${index + 1}`}</em>
                      <p>{tsk.name}</p>
                    </div>
                    <div className="dropdown-intro-right">
                      {completedTasks[tsk.id] && (
                        <em
                          style={{
                            backgroundColor: completedTasks[tsk.id]
                              ? "#0D2A1F"
                              : "#1A2330",
                            color: completedTasks[tsk.id]
                              ? "#00FF88"
                              : "#5A7A8A",
                          }}
                          className="completed"
                        >
                          Complété
                        </em>
                      )}
                      <div className="dropdown-arrow">
                        {openTask === tsk.id ? <ArrowUp /> : <ArrowDown />}
                      </div>
                    </div>
                  </div>
                  {openTask === tsk.id && (
                    <div className="dropdown-content">
                      <div className="markdown">
                        <ReactMarkDown>{tsk.content}</ReactMarkDown>
                      </div>
                      <div className="dropdown-questions">
                        {tsk.taskQuestions.map((quest: any) => {
                          return (
                            <div key={quest.id} className="dropdown-question">
                              <label
                                className="dropdown-question-title"
                                htmlFor={`q-${quest.id}`}
                              >
                                {quest.name}
                              </label>
                              <input
                                type="text"
                                id={`q-${quest.id}`}
                                value={answerQuestion[quest.id] || ""}
                                onChange={(e) =>
                                  setAnswerQuestion({
                                    ...answerQuestion,
                                    [quest.id]: e.target.value,
                                  })
                                }
                              />
                              <button
                                type="button"
                                onClick={() =>
                                  handleAnswer(
                                    quest.id,
                                    answerQuestion[quest.id] || "",
                                  )
                                }
                              >
                                Valider <ArrowRight />
                              </button>
                            </div>
                          );
                        })}
                      </div>
                      <div className="dropdown-content-bottom">
                        <p>Lis attentivement avant de valider.</p>
                        <button
                          onClick={() => handleCompletedTask(tsk.id, tsk)}
                        >
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
                <div
                  className="dropdown-challenge-intro"
                  onClick={() => setChallengeOpen(!challengeOpen)}
                >
                  <div className="dropdown-challenge-intro-left">
                    <div className="dropdown-challenge-intro-left-title">
                      Challenge
                    </div>
                    <em>{module.challenge.name}</em>
                  </div>
                  <div className="dropdown-challenge-intro-right">
                    <em>{module.challenge.point} points</em>
                    <div className="dropdown-arrow">
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
                          value={flag}
                          placeholder="FLAG{...}"
                          onChange={(e) => setFlag(e.target.value)}
                        />
                        <button type="submit">
                          Valider la tâche <ArrowRight />
                        </button>
                      </form>
                    </div>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
      {toast && (
        <Toast
          message={toast.message}
          type={toast.type}
          onClose={() => setToast(null)}
        />
      )}
    </section>
  );
}
