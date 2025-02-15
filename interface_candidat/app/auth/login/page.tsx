"use client";

import { signIn } from "next-auth/react";
import { useState } from "react";

export default function Login() {
  const [email, setEmail] = useState("");
  const [code, setCode] = useState("");

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    await signIn("credentials", {
      email,
      code,
      redirect: true,
      callbackUrl: "/dashboard",
    });
  };

  return (
    <main className="flex min-h-screen flex-col items-center justify-center bg-gradient-to-r from-green-400 to-blue-500">
      <div className="bg-white shadow-xl rounded-lg p-8 max-w-sm w-full">
        <h1 className="text-3xl font-extrabold text-center text-gray-800 mb-6">
          🗳️ Connexion Candidats
        </h1>
        <form onSubmit={handleSubmit} className="space-y-5">
          {/* Email */}
          <div>
            <label className="block text-sm font-semibold text-gray-700 mb-2">
              Email
            </label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="Votre email"
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 outline-none transition"
              required
            />
          </div>

          {/* Code */}
          <div>
            <label className="block text-sm font-semibold text-gray-700 mb-2">
              Code de connexion
            </label>
            <input
              type="password"
              value={code}
              onChange={(e) => setCode(e.target.value)}
              placeholder="Votre code"
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 outline-none transition"
              required
            />
          </div>

          {/* Bouton de connexion */}
          <button
            type="submit"
            className="w-full bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 transition-transform transform hover:scale-105"
          >
            🚀 Se connecter
          </button>
        </form>
      </div>
    </main>
  );
}
