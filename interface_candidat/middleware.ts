import { NextResponse } from 'next/server';
import { NextRequest } from 'next/server';
import { getToken } from 'next-auth/jwt';

export async function middleware(req: NextRequest) {
  const token = await getToken({ req });
  
  // Vérifier si l'utilisateur est authentifié
  if (!token) {
    return NextResponse.redirect(new URL('/auth/login', req.url)); // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié
  }

  return NextResponse.next(); // Continuer l'exécution de la requête si l'utilisateur est authentifié
}

export const config = {
  matcher: ['/dashboard'], // Appliquer ce middleware uniquement sur /dashboard
};
