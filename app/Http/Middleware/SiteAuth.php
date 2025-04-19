<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SiteData;

class SiteAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
  public function handle(Request $request, Closure $next)
{
    $domain = $request->getHost();
    $site = SiteData::where('domain', $domain)->first();

    if ($site) {
        if ($site->status == 'Pending') {
            if ($request->route()->getName() != 'install.website' && $request->route()->getName() != 'install.website.post') {
                return redirect()->route('install.website');
            }
        } elseif ($site->status == 'Active') {
            if ($request->route()->getName() == 'install.website') {
                abort(404);
            }
             return $next($request);
        }
    } else {
        if ($request->route()->getName() != 'install.website' && $request->route()->getName() != 'install.website.post') {
            return redirect()->route('install.website');
        }
    }

    return $next($request);
}


}
