//
//  WhereAmIAppDelegate.h
//  WhereAmI
//
//  Created by Charlie Key on 8/18/09.
//  Copyright Paranoid Ferret Productions 2009. All rights reserved.
//  Modified by An Ming Tan

#import <UIKit/UIKit.h>

@class WhereAmIViewController;

@interface WhereAmIAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
    WhereAmIViewController *viewController;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) IBOutlet WhereAmIViewController *viewController;

@end

