//
//  WhereAmIAppDelegate.m
//  WhereAmI
//
//  Created by Charlie Key on 8/18/09.
//  Copyright Paranoid Ferret Productions 2009. All rights reserved.
//  Modified by An Ming Tan

#import "WhereAmIAppDelegate.h"
#import "WhereAmIViewController.h"

@implementation WhereAmIAppDelegate

@synthesize window;
@synthesize viewController;


- (void)applicationDidFinishLaunching:(UIApplication *)application {    
    
    // Override point for customization after app launch    
    [window addSubview:viewController.view];
    [window makeKeyAndVisible];
}


- (void)dealloc {
    [viewController release];
    [window release];
    [super dealloc];
}


@end
