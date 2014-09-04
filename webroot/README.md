#Xtreme 2.0
Aight. Here we go again. I threw down some basic Ember documents in order to get started and to start envisioning where you'll do stuff. First thing: routing. I'll go make everything pretty, but we need these to exist:

/splash
/splash/refresh



/menu
either each submenu (ie:/menu/pizza) or this might be where :slug comes into play, and loading the menu content is just a menuController action

/menu/specials/:slug (specials & deals will have unqiue & probably, to some extent, idiosyncratic presentational demands)

/menu/view/:slug (definitely not going to have different routes per "orb" as it were, and I appreciate that this naming convention may have lost it's utility)

let me know if this is ambiguous or too little work. 

