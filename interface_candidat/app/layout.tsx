import { Providers } from "@/components/Providers";
import "@/styles/globals.css";

export const metadata = {
  title: "Interface Candidats",
  description: "Suivi des parrainages en temps réel",
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="fr">
      <body className="bg-gray-100 text-gray-900">
        <Providers>{children}</Providers>
      </body>
    </html>
  );
}
