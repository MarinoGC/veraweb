import { Sjab6Page } from './app.po';

describe('sjab6 App', function() {
  let page: Sjab6Page;

  beforeEach(() => {
    page = new Sjab6Page();
  });

  it('should display message saying app works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('app works!');
  });
});
