//
//  MK_HomeViewController.m
//  HeSDK
//
//  Created by marku on 12/10/20.
//  Copyright (c) 2012年 marku. All rights reserved.
//

#import "MK_HomeViewController.h"

@interface MK_HomeViewController () {
    NSString* currentTabName;
    CLLocationManager* locationManager;
    
    UIAlertView* plzWaitAlert;
    UIActivityIndicatorView* plzWaitIndicator;
    
    double current_latitude;
    double current_longitude;
    
    BOOL isGpsOn;
    BOOL isWaitingForGPS;
}

@property (strong, nonatomic) IBOutlet UIButton *activityTabButton;
@property (strong, nonatomic) IBOutlet UIButton *groupTabButton;
@property (strong, nonatomic) IBOutlet UIButton *discountTabButton;
@property (strong, nonatomic) IBOutlet UIButton *trashTabButt;

@property (strong, nonatomic) IBOutlet UIWebView *mainWebview;

@end

@implementation MK_HomeViewController

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
	// Do any additional setup after loading the view.
    
    // reading GPS data
    isGpsOn = NO;
    locationManager = [CLLocationManager new];
    if([CLLocationManager locationServicesEnabled]) {
        isGpsOn = YES;
        isWaitingForGPS = YES;
        locationManager.delegate = self;
        locationManager.distanceFilter = kCLDistanceFilterNone;
        locationManager.desiredAccuracy = kCLLocationAccuracyBest;
        [locationManager startUpdatingLocation];
        
        //等待gps定位後才繼續
    } else {
        isGpsOn = NO;
        [self alertWithMessage:@"無法取得GPS資料，請於設定打開。"];
    }
    
    self.mainWebview.delegate = self;

}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma  mark - (hulala) 
- (void) loadMainWebviewWithUrl:(NSString*) urlString {
    if(isGpsOn) {
        NSString* encodedLatitude = [[NSString stringWithFormat:@"%f", current_latitude] stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
        NSString* encodedLongitude = [[NSString stringWithFormat:@"%f", current_longitude] stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
        urlString = [NSString stringWithFormat:@"%@/index/%@/%@", urlString, encodedLatitude, encodedLongitude];
    }
    NSLog(@"loading url:%@", urlString);
    NSURL* url = [[NSURL alloc] initWithString:urlString];
    NSURLRequest* request = [[NSURLRequest alloc] initWithURL:url];
    [self.mainWebview loadRequest:request];
    [self showLoadingPleaseWaitAlert];
}

#pragma mark - (Alerts) 
- (void) alertWithMessage:(NSString*) message {
    UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Alert"
                                                    message:message
                                                   delegate:nil
                                          cancelButtonTitle:@"OK"
                                          otherButtonTitles:nil] ;
    [alert show];
}

-(void) hideLoadingPleaseWaitAlert {
    // 關閉菊花輪
    if(plzWaitAlert != nil) {
        [plzWaitAlert dismissWithClickedButtonIndex:0 animated:YES];
        plzWaitIndicator = nil;
        plzWaitAlert = nil;
    }
}

- (void) showLoadingPleaseWaitAlert {
    // 菊花輪 轉阿轉
    plzWaitAlert = [[UIAlertView alloc] initWithTitle:@"資料讀取中"
                                              message:nil
                                             delegate:nil
                                    cancelButtonTitle:nil
                                    otherButtonTitles: nil];
    [plzWaitAlert show];
    plzWaitIndicator = [[UIActivityIndicatorView alloc] initWithFrame:CGRectZero];
    plzWaitIndicator.center = CGPointMake(plzWaitAlert.bounds.size.width/2, plzWaitAlert.bounds.size.height/2);
    [plzWaitIndicator startAnimating];
    [plzWaitAlert addSubview:plzWaitIndicator];
}

#pragma mark - (Tab buttons) 
-(void) goToActivityTab {
    currentTabName = @"activity";
    [self loadMainWebviewWithUrl:@"http://lohas.adct.org.tw/index.php/home"];
    [self deselectAllOtherTabButtonButNotCurrent];
}

- (void) goToGroupsTab {
    currentTabName = @"groups";
    [self loadMainWebviewWithUrl:@"http://lohas.adct.org.tw/index.php/active"];
    [self deselectAllOtherTabButtonButNotCurrent];
}

- (void) goToDiscount {
    currentTabName = @"discount";
    [self loadMainWebviewWithUrl:@"http://lohas.adct.org.tw/index.php/products"];
    [self deselectAllOtherTabButtonButNotCurrent];
}

- (void) goToTrash {
    currentTabName = @"trash";
    [self loadMainWebviewWithUrl:@"http://lohas.adct.org.tw/index.php/garbage"];
    [self deselectAllOtherTabButtonButNotCurrent];
}
- (IBAction)activitiesTabButtonClicked:(UIButton *)sender {
    [self goToActivityTab];
}

- (IBAction)trashTabButtonClicked:(UIButton *)sender {
    [self goToTrash];
}

- (IBAction)discountTabButtonClicked:(UIButton *)sender {
    [self goToDiscount];
}

- (IBAction)groupsTabButtonClicked:(UIButton *)sender {
    [self goToGroupsTab];
}

- (void) deselectAllOtherTabButtonButNotCurrent {
    self.activityTabButton.selected = NO;
    self.groupTabButton.selected = NO;
    self.discountTabButton.selected = NO;
    self.trashTabButt.selected = NO;
    
    if([currentTabName isEqualToString:@"activity"]) {
        self.activityTabButton.selected = YES;
    } else if([currentTabName isEqualToString:@"groups"]) {
        self.groupTabButton.selected = YES;
    } else if([currentTabName isEqualToString:@"discount"]) {
        self.discountTabButton.selected = YES;
    } else if([currentTabName isEqualToString:@"trash"]) {
        self.trashTabButt.selected = YES;
    }
    
}

#pragma mark - (GPS delegate)
- (void) locationManager:(CLLocationManager *)manager didUpdateLocations:(NSArray *)locations {
    int locationCounts = locations.count;
    CLLocation* newLocation = [locations objectAtIndex:locationCounts-1];
    CLLocationCoordinate2D coordinate =  newLocation.coordinate;
    CLLocationDegrees latitude = coordinate.latitude;
    CLLocationDegrees longitude = coordinate.longitude;
    
    CLLocationAccuracy horizontal = newLocation.horizontalAccuracy;
    CLLocationAccuracy vertial = newLocation.verticalAccuracy;
    
    CLLocationDistance altitude = newLocation.altitude;
    
    NSDate* timestamp = [newLocation timestamp];
    
    NSLog([newLocation description]);
    
    // 與前一次地點的距離
    if(locationCounts > 1) {
        CLLocation* lastLocation = [locations objectAtIndex:locationCounts-2];
        CLLocationDistance delta = [newLocation distanceFromLocation:lastLocation];
        NSLog([NSString stringWithFormat:@"distance from last location: %f", delta]);
    }
    
    current_latitude = latitude;
    current_longitude = longitude;
    
    if(isWaitingForGPS) {
        isWaitingForGPS = NO;
        [self goToActivityTab];
    }
}

- (void) locationManager:(CLLocationManager *)manager didFailWithError:(NSError *)error {
    NSLog(@"GPS error, %@", [error localizedDescription]);
    isGpsOn = NO;
    [self alertWithMessage:@"失去無法取得GPS定位，請於設定打開。"];
}

#pragma mark - (UIWebViewDelegate) 

- (BOOL)                webView:(UIWebView *)webView
     shouldStartLoadWithRequest:(NSURLRequest *)request
                 navigationType:(UIWebViewNavigationType)navigationType {
    
    NSLog(@"should webview begins to load. %@", request.URL.absoluteString);
    return YES;
}

- (void) webViewDidStartLoad:(UIWebView *)webView {
    NSLog(@"webview start loading...");
    //[self showLoadingPleaseWaitAlert];
}

- (void) webView:(UIWebView *)webView didFailLoadWithError:(NSError *)error {
    NSLog(@"webview fail load. %@", error.localizedDescription);
    [self hideLoadingPleaseWaitAlert];
}

- (void) webViewDidFinishLoad:(UIWebView *)webView {
    NSLog(@"webview finish load.");
    // 取消長按 uiwebview上的連結 會跳出actionsheet
    [webView stringByEvaluatingJavaScriptFromString:@"document.body.style.webkitTouchCallout='none'; document.body.style.KhtmlUserSelect='none'"];
    [self hideLoadingPleaseWaitAlert];
}

@end
