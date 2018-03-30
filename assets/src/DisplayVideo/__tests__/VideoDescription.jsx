import { render, shallow } from 'enzyme';

import VideoDescription from '../VideoDescription';

describe('<VideoDescription />', () => {
  let description;
  let className;

  beforeEach(() => {
    description = '# Hello world \n This is a paragraph';
    className = 'test-test some-css-class';
  });

  it('should render empty description properly', () => {
    const wrapper = render(<VideoDescription />);

    expect(wrapper).toMatchSnapshot();
  });

  it('should render description properly', () => {
    const wrapper = render(<VideoDescription description={description} />);

    expect(wrapper).toMatchSnapshot();
  });

  it('should not render ReactMarkdown when empty', () => {
    const wrapper = render(<VideoDescription />);

    expect(wrapper.find('ReactMarkdown').length).toBe(0);
  });

  it('should render ReactMarkdown when not empty', () => {
    // Must use shallow() here, as render() will tell ReactMarkdown to run.
    const wrapper = shallow(<VideoDescription description={description} />);

    expect(wrapper.find('ReactMarkdown').length).toBe(1);
  });

  it('should place className on <section>', () => {
    const wrapper = shallow(<VideoDescription className={className} />);

    expect(wrapper.find('section').hasClass(className)).toBeTruthy();
  });
});
